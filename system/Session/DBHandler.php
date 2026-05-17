<?php

namespace System\Session;

use PDO;
use PDOException;
use SessionHandlerInterface;
use System\Core\Language;

/**
 * Custom session handler that stores session data in a database (MySQL or PostgreSQL),
 * with optional encryption and prefix support.
 */
class DBHandler implements SessionHandlerInterface {
    private const ENCRYPTION_FORMAT = 'v2';
    private const ENCRYPTION_MIN_KEY_LENGTH = 32;
    private const OPENSSL_GCM_CIPHER = 'aes-256-gcm';
    private const OPENSSL_CBC_CIPHER = 'aes-256-cbc';

    /**
     * PDO connection instance used to interact with the database.
     */
    private PDO $pdo;

    /**
     * The current database driver name (e.g. mysql, pgsql).
     * Used to determine syntax for UPSERTs and garbage collection.
     */
    private string $driver;

    /**
     * Optional prefix applied to all session IDs stored in the database.
     * Useful to isolate session data across different applications or environments.
     */
    private string $prefix;

    /**
     * Optional derived binary encryption key for securing session data at rest.
     * When set, session payloads are encrypted with authenticated encryption.
     */
    private ?string $encryptionKey;

    /**
     * Constructor receives the PDO instance, optional prefix and encryption key.
     */
    public function __construct(PDO $pdo, ?string $prefix = null, ?string $encryptionKey = null) {
        $this->pdo = $pdo;
        $this->driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
        $this->prefix = $prefix ?? '';
        $this->encryptionKey = self::resolveEncryptionKey($encryptionKey);

        $this->ensureTableExists();
    }

    /**
     * Required by interface – not used in this implementation
     */
    public function open($path, $name): bool { return true; }

    /**
     * Required by interface – not used in this implementation
     */
    public function close(): bool { return true; }

    /**
     * Reads the session data from the database using the session ID.
     */
    public function read($id): string {
        $stmt = $this->pdo->prepare("SELECT data FROM sessions WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $this->prefix . $id]);
        $data = $stmt->fetchColumn() ?: '';

        return $this->encryptionKey ? $this->decrypt($data) : $data;
    }

    /**
     * Writes session data to the database.
     * Uses INSERT ... ON DUPLICATE/CONFLICT to perform UPSERTs.
     */
    public function write($id, $data): bool {
        $id = $this->prefix . $id;
        $data = $this->encryptionKey ? $this->encrypt($data) : $data;

        $ip = $_SERVER['REMOTE_ADDR'] ?? null;
        $agent = $_SERVER['HTTP_USER_AGENT'] ?? null;

        if ($this->driver === 'pgsql') {
            $sql = <<<SQL
                INSERT INTO sessions (id, data, ip, user_agent, updated_at)
                VALUES (:id, :data, :ip, :ua, NOW())
                ON CONFLICT (id)
                DO UPDATE SET data = EXCLUDED.data, ip = EXCLUDED.ip, user_agent = EXCLUDED.user_agent, updated_at = NOW()
            SQL;
        } else {
            $sql = <<<SQL
                INSERT INTO sessions (id, data, ip, user_agent, updated_at)
                VALUES (:id, :data, :ip, :ua, NOW())
                ON DUPLICATE KEY UPDATE data = VALUES(data), ip = VALUES(ip), user_agent = VALUES(user_agent), updated_at = NOW()
            SQL;
        }

        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([
            'id'   => $id,
            'data' => $data,
            'ip'   => $ip,
            'ua'   => $agent
        ]);
    }

    /**
     * Deletes the session from the database by ID.
     */
    public function destroy($id): bool {
        $stmt = $this->pdo->prepare("DELETE FROM sessions WHERE id = :id");
        return $stmt->execute(['id' => $this->prefix . $id]);
    }

    /**
     * Garbage collector – removes old session entries based on lifetime.
     */
    public function gc($max_lifetime): int|false {
        $sql = match ($this->driver) {
            'pgsql' => "DELETE FROM sessions WHERE updated_at < NOW() - INTERVAL ':max seconds'",
            'mysql' => "DELETE FROM sessions WHERE updated_at < NOW() - INTERVAL :max SECOND",
            default => throw new \RuntimeException(Language::get("system.database.driver.not-found"))
        };

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':max', $max_lifetime, PDO::PARAM_INT);
        return $stmt->execute() ? $stmt->rowCount() : false;
    }

    /**
     * Creates the sessions table if it does not exist.
     */
    private function ensureTableExists(): void {
        try {
            if ($this->driver === 'pgsql') {
                $sql = <<<SQL
                    CREATE TABLE IF NOT EXISTS sessions (
                        id VARCHAR(128) PRIMARY KEY,
                        data TEXT NOT NULL,
                        ip VARCHAR(45),
                        user_agent TEXT,
                        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                    );
                SQL;
            } else {
                $sql = <<<SQL
                    CREATE TABLE IF NOT EXISTS sessions (
                        id VARCHAR(128) NOT NULL PRIMARY KEY,
                        data TEXT NOT NULL,
                        ip VARCHAR(45),
                        user_agent TEXT,
                        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
                    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
                SQL;
            }

            $this->pdo->exec($sql);
        } catch (PDOException $e) {
            throw new \RuntimeException(Language::get("system.database.tables.error.info", ["error" => $e->getMessage()]));
        }
    }

    /**
     * Encrypts session data using a versioned authenticated format.
     */
    private function encrypt(string $data): string {
        if ($data === '' || $this->encryptionKey === null) {
            return "";
        }

        if (self::canUseSodium()) {
            $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);
            $ciphertext = sodium_crypto_secretbox($data, $nonce, $this->encryptionKey);

            return implode(':', [
                self::ENCRYPTION_FORMAT,
                'sodium',
                self::encode($nonce),
                self::encode($ciphertext),
            ]);
        }

        if (in_array(self::OPENSSL_GCM_CIPHER, openssl_get_cipher_methods(), true)) {
            $nonce = random_bytes(12);
            $tag = '';
            $ciphertext = openssl_encrypt(
                $data,
                self::OPENSSL_GCM_CIPHER,
                $this->encryptionKey,
                OPENSSL_RAW_DATA,
                $nonce,
                $tag,
                self::ENCRYPTION_FORMAT . ':gcm',
                16
            );

            if (!is_string($ciphertext) || $tag === '') {
                throw new \RuntimeException(Language::get('system.session.encryption.error') ?? 'Session encryption failed.');
            }

            return implode(':', [
                self::ENCRYPTION_FORMAT,
                'gcm',
                self::encode($nonce),
                self::encode($tag),
                self::encode($ciphertext),
            ]);
        }

        $ivLength = openssl_cipher_iv_length(self::OPENSSL_CBC_CIPHER);
        if ($ivLength === false) {
            throw new \RuntimeException(Language::get('system.session.encryption.error') ?? 'Session encryption failed.');
        }

        $iv = random_bytes($ivLength);
        $ciphertext = openssl_encrypt(
            $data,
            self::OPENSSL_CBC_CIPHER,
            $this->encryptionKey,
            OPENSSL_RAW_DATA,
            $iv
        );

        if (!is_string($ciphertext)) {
            throw new \RuntimeException(Language::get('system.session.encryption.error') ?? 'Session encryption failed.');
        }

        $mac = hash_hmac('sha256', self::ENCRYPTION_FORMAT . ':cbc:' . $iv . $ciphertext, $this->encryptionKey, true);

        return implode(':', [
            self::ENCRYPTION_FORMAT,
            'cbc',
            self::encode($iv),
            self::encode($ciphertext),
            self::encode($mac),
        ]);
    }

    /**
     * Decrypts previously encrypted session data.
     */
    private function decrypt(string $data): string {
        if ($data === '' || $this->encryptionKey === null) {
            return "";
        }

        $parts = explode(':', $data);
        if (($parts[0] ?? '') !== self::ENCRYPTION_FORMAT) {
            return '';
        }

        return match ($parts[1] ?? '') {
            'sodium' => $this->decryptSodium($parts),
            'gcm' => $this->decryptGcm($parts),
            'cbc' => $this->decryptCbc($parts),
            default => '',
        };
    }

    private static function resolveEncryptionKey(?string $encryptionKey): ?string {
        $encryptionKey = is_string($encryptionKey) ? trim($encryptionKey) : '';

        if ($encryptionKey === '') {
            return null;
        }

        if (strlen($encryptionKey) < self::ENCRYPTION_MIN_KEY_LENGTH) {
            throw new \RuntimeException(Language::get('system.session.encrypt-key.invalid') ?? 'Invalid session encryption key.');
        }

        return hash('sha256', $encryptionKey, true);
    }

    private static function canUseSodium(): bool {
        return function_exists('sodium_crypto_secretbox')
            && function_exists('sodium_crypto_secretbox_open')
            && defined('SODIUM_CRYPTO_SECRETBOX_NONCEBYTES');
    }

    private static function encode(string $value): string {
        return base64_encode($value);
    }

    private static function decode(string $value): ?string {
        $decoded = base64_decode($value, true);

        return is_string($decoded) ? $decoded : null;
    }

    /**
     * @param array<int, string> $parts
     */
    private function decryptSodium(array $parts): string {
        if (!self::canUseSodium() || count($parts) !== 4) {
            return '';
        }

        $nonce = self::decode($parts[2]);
        $ciphertext = self::decode($parts[3]);

        if ($nonce === null || $ciphertext === null) {
            return '';
        }

        $plaintext = sodium_crypto_secretbox_open($ciphertext, $nonce, $this->encryptionKey);

        return is_string($plaintext) ? $plaintext : '';
    }

    /**
     * @param array<int, string> $parts
     */
    private function decryptGcm(array $parts): string {
        if (count($parts) !== 5 || !in_array(self::OPENSSL_GCM_CIPHER, openssl_get_cipher_methods(), true)) {
            return '';
        }

        $nonce = self::decode($parts[2]);
        $tag = self::decode($parts[3]);
        $ciphertext = self::decode($parts[4]);

        if ($nonce === null || $tag === null || $ciphertext === null) {
            return '';
        }

        $plaintext = openssl_decrypt(
            $ciphertext,
            self::OPENSSL_GCM_CIPHER,
            $this->encryptionKey,
            OPENSSL_RAW_DATA,
            $nonce,
            $tag,
            self::ENCRYPTION_FORMAT . ':gcm'
        );

        return is_string($plaintext) ? $plaintext : '';
    }

    /**
     * @param array<int, string> $parts
     */
    private function decryptCbc(array $parts): string {
        if (count($parts) !== 5) {
            return '';
        }

        $iv = self::decode($parts[2]);
        $ciphertext = self::decode($parts[3]);
        $mac = self::decode($parts[4]);

        if ($iv === null || $ciphertext === null || $mac === null) {
            return '';
        }

        $expectedMac = hash_hmac('sha256', self::ENCRYPTION_FORMAT . ':cbc:' . $iv . $ciphertext, $this->encryptionKey, true);
        if (!hash_equals($expectedMac, $mac)) {
            return '';
        }

        $plaintext = openssl_decrypt(
            $ciphertext,
            self::OPENSSL_CBC_CIPHER,
            $this->encryptionKey,
            OPENSSL_RAW_DATA,
            $iv
        );

        return is_string($plaintext) ? $plaintext : '';
    }
}
