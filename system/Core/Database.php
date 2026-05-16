<?php
namespace System\Core;

use PDO;
use PDOException;
use System\Config\Database AS ConfigDatabase;

/**
 * Database connection and query abstraction layer.
 *
 * Provides lazy PDO connections, prepared statement execution, SELECT helpers,
 * and transaction helpers for the default database and named connections.
 */
class Database {
    /**
     * Cached PDO connections by connection key.
     *
     * @var array<string, PDO>
     */
    private static array $connections = [];

    /**
     * Transaction nesting level by connection key.
     *
     * @var array<string, int>
     */
    private static array $transactionLevels = [];
    
    /**
     * Builds a deterministic savepoint name for the given transaction nesting level.
     *
     * @param int $level The transaction nesting level associated with the savepoint.
     * @return string The generated savepoint name.
     */
    private static function getSavepointName(int $level): string {
        return 'sp_' . $level;
    }

    /**
     * Establish and return a PDO database connection.
     *
     * @param string $connection Connection key. Use default for unsuffixed DB_* config.
     * @throws \RuntimeException If configuration is missing or connection fails.
     * @return PDO
     */
    public static function connect(string $connection = ConfigDatabase::DEFAULT_CONNECTION): PDO {
        $connection = ConfigDatabase::normalizeConnectionName($connection);

        if (!empty(self::$connections[$connection])) {
            return self::$connections[$connection];
        }

        $config = ConfigDatabase::connection($connection);

        if ($config === null) {
            $message = $connection === ConfigDatabase::DEFAULT_CONNECTION
                ? Language::get('system.database.driver.not-found')
                : Language::get('system.database.connection.not-found.info', ['connection' => $connection]);

            throw new \RuntimeException($message);
        }

        $dsn = match ($config['driver']) {
            'pgsql' => "pgsql:host={$config['host']};port={$config['port']};dbname={$config['name']}",
            'mysql' => "mysql:host={$config['host']};port={$config['port']};dbname={$config['name']};charset={$config['charset']}",
            default => throw new \RuntimeException(Language::get('system.database.driver.not-supported.info', ['driver' => $config['driver']])),
        };

        try {
            self::$connections[$connection] = new PDO($dsn, $config['user'], $config['pass'], [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
            self::$transactionLevels[$connection] = 0;

            return self::$connections[$connection];
        } catch (PDOException $e) {
            throw new \RuntimeException(Language::get('system.database.connection.error.info', ['error' => $e->getMessage()]));
        }
    }

    /**
     * Register or replace a runtime database connection config.
     *
     * @param string $connection
     * @param array<string, mixed> $config
     * @return void
     */
    public static function configure(string $connection, array $config): void {
        $connection = ConfigDatabase::normalizeConnectionName($connection);
        ConfigDatabase::configure($connection, $config);
        self::disconnect($connection);
    }

    /**
     * Remove a runtime connection config and close any cached PDO for it.
     *
     * @param string $connection
     * @return void
     */
    public static function forgetConnection(string $connection): void {
        $connection = ConfigDatabase::normalizeConnectionName($connection);
        self::disconnect($connection);
        ConfigDatabase::forgetConnection($connection);
    }

    /**
     * Check whether a connection config exists.
     *
     * @param string $connection
     * @return bool
     */
    public static function hasConnection(string $connection): bool {
        return ConfigDatabase::hasConnection($connection);
    }

    /**
     * Return all configured connection names.
     *
     * @return array<int, string>
     */
    public static function connectionNames(): array {
        return ConfigDatabase::connectionNames();
    }

    /**
     * Checks whether a transaction is currently active.
     *
     * @param string $connection
     * @return bool
     */
    public static function isInTransaction(string $connection = ConfigDatabase::DEFAULT_CONNECTION): bool {
        $connection = ConfigDatabase::normalizeConnectionName($connection);

        return (self::$transactionLevels[$connection] ?? 0) > 0;
    }

    /**
     * Starts a new database transaction if none is currently active.
     *
     * @param string $connection
     * @return bool
     */
    public static function startTransaction(string $connection = ConfigDatabase::DEFAULT_CONNECTION): bool {
        $connection = ConfigDatabase::normalizeConnectionName($connection);
        $pdo = self::connect($connection);
        $level = self::$transactionLevels[$connection] ?? 0;

        if ($level === 0) {
            $ret = $pdo->beginTransaction();
            if (!$ret) {
                throw new \RuntimeException(Language::get('system.database.transaction.start.error'));
            }

            self::$transactionLevels[$connection] = 1;
            return true;
        }

        $savepoint = self::getSavepointName($level);
        self::statement("SAVEPOINT {$savepoint}", [], $connection);
        self::$transactionLevels[$connection] = $level + 1;

        return true;
    }

    /**
     * Commits the current database transaction if one is active.
     *
     * @param string $connection
     * @return bool
     */
    public static function commitTransaction(string $connection = ConfigDatabase::DEFAULT_CONNECTION): bool {
        $connection = ConfigDatabase::normalizeConnectionName($connection);
        $level = self::$transactionLevels[$connection] ?? 0;

        if ($level === 0) {
            return true;
        }

        $pdo = self::connect($connection);

        if ($level === 1) {
            $ret = $pdo->commit();
            if (!$ret) {
                throw new \RuntimeException(Language::get('system.database.transaction.commit.error'));
            }

            self::$transactionLevels[$connection] = 0;
            return true;
        }

        $savepointLevel = $level - 1;
        $savepoint = self::getSavepointName($savepointLevel);
        self::statement("RELEASE SAVEPOINT {$savepoint}", [], $connection);
        self::$transactionLevels[$connection] = $level - 1;

        return true;
    }

    /**
     * Rolls back the current database transaction if one is active.
     *
     * @param string $connection
     * @return bool
     */
    public static function rollbackTransaction(string $connection = ConfigDatabase::DEFAULT_CONNECTION): bool {
        $connection = ConfigDatabase::normalizeConnectionName($connection);
        $level = self::$transactionLevels[$connection] ?? 0;

        if ($level === 0) {
            return true;
        }

        $pdo = self::connect($connection);

        if ($level === 1) {
            $ret = $pdo->rollBack();
            if (!$ret) {
                throw new \RuntimeException(Language::get('system.database.transaction.rollback.error'));
            }

            self::$transactionLevels[$connection] = 0;
            return true;
        }

        $savepointLevel = $level - 1;
        $savepoint = self::getSavepointName($savepointLevel);

        self::statement("ROLLBACK TO SAVEPOINT {$savepoint}", [], $connection);
        self::statement("RELEASE SAVEPOINT {$savepoint}", [], $connection);
        self::$transactionLevels[$connection] = $level - 1;

        return true;
    }

    /**
     * Execute a raw SQL statement.
     *
     * @param string $sql SQL query to execute.
     * @param array $params Parameters to bind.
     * @param string $connection Connection key.
     * @return bool
     */
    public static function statement(string $sql, array $params = [], string $connection = ConfigDatabase::DEFAULT_CONNECTION): bool {
        $pdo = self::connect($connection);
        $stmt = $pdo->prepare($sql);

        return $stmt->execute($params);
    }

    /**
     * Retrieves the ID of the last inserted row or sequence value.
     *
     * @param string $connection
     * @return string|false
     */
    public static function getLastInsertedID(string $connection = ConfigDatabase::DEFAULT_CONNECTION): string|false {
        return self::connect($connection)->lastInsertId();
    }

    /**
     * Fetch multiple rows from a SELECT query.
     *
     * @param string $sql SQL SELECT query.
     * @param array $params Parameters to bind.
     * @param string|null $key Optional column to extract.
     * @param string $connection Connection key.
     * @return array
     */
    public static function select(
        string $sql,
        array $params = [],
        ?string $key = null,
        string $connection = ConfigDatabase::DEFAULT_CONNECTION
    ): array {
        $pdo = self::connect($connection);
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        $rows = $stmt->fetchAll();

        if ($key !== null) {
            return array_map(fn($row) => $row[$key] ?? null, $rows);
        }

        return $rows;
    }

    /**
     * Fetch a single row or a single value from a SELECT query.
     *
     * @param string $sql SQL SELECT query.
     * @param array $params Parameters to bind.
     * @param string|null $key Optional column to extract.
     * @param string $connection Connection key.
     * @return mixed
     */
    public static function selectRow(
        string $sql,
        array $params = [],
        ?string $key = null,
        string $connection = ConfigDatabase::DEFAULT_CONNECTION
    ): mixed {
        $pdo = self::connect($connection);
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        $row = $stmt->fetch();

        if (!$row) {
            return null;
        }

        if ($key !== null) {
            return $row[$key] ?? null;
        }

        return $row;
    }

    /**
     * Disconnect one connection, or all connections when no key is provided.
     *
     * @param string|null $connection
     * @return void
     */
    public static function disconnect(?string $connection = null): void {
        if ($connection === null) {
            self::$connections = [];
            self::$transactionLevels = [];
            return;
        }

        $connection = ConfigDatabase::normalizeConnectionName($connection);
        unset(self::$connections[$connection], self::$transactionLevels[$connection]);
    }
}
