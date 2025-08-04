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
     * Optional encryption key for securing session data at rest.
     * When set, session payloads are encrypted using AES-256-CBC.
     */
    private ?string $encryptionKey;

    /**
     * Constructor receives the PDO instance, optional prefix and encryption key.
     */
    public function __construct(PDO $pdo, ?string $prefix = null, ?string $encryptionKey = null) {
        $this->pdo = $pdo;
        $this->driver = $pdo->getAttribute(PDO::ATTR_DRIVER_NAME);
        $this->prefix = $prefix ?? '';
        $this->encryptionKey = $encryptionKey;

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
            throw new \RuntimeException(Language::get("system.database.tables.error.info") . $e->getMessage());
        }
    }

    /**
     * Encrypts session data using AES-256-CBC and base64 encoding.
     */
    private function encrypt(string $data): string {
        return base64_encode(openssl_encrypt($data, 'aes-256-cbc', $this->encryptionKey, 0, substr($this->encryptionKey, 0, 16)));
    }

    /**
     * Decrypts previously encrypted session data.
     */
    private function decrypt(string $data): string {
        return openssl_decrypt(base64_decode($data), 'aes-256-cbc', $this->encryptionKey, 0, substr($this->encryptionKey, 0, 16)) ?: '';
    }
}
