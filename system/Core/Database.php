<?php
namespace System\Core;

use PDO;
use PDOException;
use System\Config\Database AS ConfigDatabase;
use System\Config\Globals;

/**
 * Database connection and query abstraction layer.
 *
 * Provides a singleton PDO connection and helper methods for executing
 * prepared statements, fetching results, and managing the connection lifecycle.
 *
 * Supports MySQL and PostgreSQL drivers as defined in environment settings.
 */
class Database {
    /**
     * Cached PDO connection (singleton).
     *
     * @var PDO|null
     */
    private static ?PDO $connection = null;

    /**
     * Nível atual da transação.
     * 0 = sem transação
     * 1 = transação principal
     * 2+ = níveis aninhados via savepoint
     *
     * @var int
     */
    private static int $transactionLevel = 0;
    
    /**
     * Builds a deterministic savepoint name for the given transaction nesting level.
     *
     * Savepoints are used to simulate nested transactions when a transaction is
     * already active. The generated name is internal to the database layer and
     * should be unique per nesting level within the current transaction scope.
     *
     * Example:
     * - level 1 => sp_1
     * - level 2 => sp_2
     *
     * @param int $level The transaction nesting level associated with the savepoint.
     * @return string The generated savepoint name.
     */
    private static function getSavepointName(int $level): string {
        return 'sp_' . $level;
    }

    /**
     * Establish and return the PDO database connection.
     *
     * Loads credentials and configuration from Globals.
     *
     * @throws \RuntimeException If configuration is missing or connection fails.
     * @return PDO
     */
    public static function connect(): PDO {
        // Return existing connection if already established
        if (!empty(self::$connection)) {
            return self::$connection;
        }

        // Ensure a driver is configured
        if(ConfigDatabase::isNone()) {
            throw new \RuntimeException(Language::get("system.database.driver.not-found"));
        }

        // Load connection settings from environment
        $driver = ConfigDatabase::env();
        $host   = Globals::env('DB_HOST');
        $port   = Globals::env('DB_PORT');
        $name   = Globals::env('DB_NAME');
        $user   = Globals::env('DB_USER');
        $pass   = Globals::env('DB_PASS');
        $charset = Globals::env('DB_CHARSET') ?? 'utf8';

        // Fallback to default ports if not defined
        if (empty($port)) {
            $port = match ($driver) {
                'pgsql' => 5432,
                'mysql' => 3306,
                default => null
            };
        }

        // Validate required parameters
        $required = [
            'DB_DRIVER' => !empty($driver),
            'DB_HOST'   => !empty($host),
            'DB_PORT'   => !empty($port),
            'DB_NAME'   => !empty($name),
            'DB_USER'   => !empty($user)
        ];

        $missing = [];
        foreach ($required AS $key => $value) {
            if (empty($value)) {
                $missing[] = $key;
            }
        }

        if (!empty($missing)) {
            throw new \RuntimeException(
                Language::get("system.database.parameters.required.info", ["parameters" => implode(', ', $missing)])
            );
        }

        // Build the DSN string based on driver
        $dsn = match ($driver) {
            'pgsql' => "pgsql:host={$host};port={$port};dbname={$name}",
            'mysql' => "mysql:host={$host};port={$port};dbname={$name};charset={$charset}",
            default => throw new \RuntimeException(Language::get("system.database.driver.not-supported.info", ["driver" => $driver])),
        };

        // Attempt to create PDO connection
        try {
            self::$connection = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
            self::$transactionLevel = 0;

            return self::$connection;
        } catch (PDOException $e) {
            throw new \RuntimeException(Language::get("system.database.connection.error.info", ["error" => $e->getMessage()]));
        }
    }

    /**
     * Checks whether a transaction is currently active.
     *
     * @return bool True if a transaction is in progress, false otherwise.
     */
    public static function isInTransaction(): bool {
        return self::$transactionLevel > 0;
    }

    /**
     * Starts a new database transaction if none is currently active.
     *
     * @return bool True if the transaction was successfully started or is already active, false on failure.
     */
    public static function startTransaction(): bool {
        $pdo = self::connect();

        if (self::$transactionLevel === 0) {
            $ret = $pdo->beginTransaction();
            if (!$ret) {
                throw new \RuntimeException(Language::get("system.database.transaction.start.error"));
            }

            self::$transactionLevel = 1;
            return true;
        }

        $savepoint = self::getSavepointName(self::$transactionLevel);
        self::statement("SAVEPOINT {$savepoint}");
        self::$transactionLevel++;

        return true;
    }

    /**
     * Commits the current database transaction if one is active.
     *
     * @return bool True if the transaction was committed or none was active, false on failure.
     */
    public static function commitTransaction(): bool {
        if (self::$transactionLevel === 0) {
            return true;
        }

        $pdo = self::connect();

        if (self::$transactionLevel === 1) {
            $ret = $pdo->commit();
            if (!$ret) {
                throw new \RuntimeException(Language::get("system.database.transaction.commit.error"));
            }

            self::$transactionLevel = 0;
            return true;
        }

        $savepointLevel = self::$transactionLevel - 1;
        $savepoint = self::getSavepointName($savepointLevel);
        self::statement("RELEASE SAVEPOINT {$savepoint}");
        self::$transactionLevel--;

        return true;
    }

    /**
     * Rolls back the current database transaction if one is active.
     *
     * @return bool True if the transaction was rolled back or none was active, false on failure.
     */
    public static function rollbackTransaction(): bool {
        if (self::$transactionLevel === 0) {
            return true;
        }

        $pdo = self::connect();

        if (self::$transactionLevel === 1) {
            $ret = $pdo->rollBack();
            if (!$ret) {
                throw new \RuntimeException(Language::get("system.database.transaction.rollback.error"));
            }

            self::$transactionLevel = 0;
            return true;
        }

        $savepointLevel = self::$transactionLevel - 1;
        $savepoint = self::getSavepointName($savepointLevel);

        self::statement("ROLLBACK TO SAVEPOINT {$savepoint}");
        self::statement("RELEASE SAVEPOINT {$savepoint}");
        self::$transactionLevel--;

        return true;
    }

    /**
     * Execute a raw SQL statement (e.g., INSERT, UPDATE, DELETE).
     *
     * @param string $sql    SQL query to execute.
     * @param array  $params Parameters to bind (optional).
     * @return bool True on success, false on failure.
     */
    public static function statement(string $sql, array $params = []): bool {
        $pdo = self::connect();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
    }

    /**
     * Retrieves the ID of the last inserted row or sequence value.
     *
     * This method wraps PDO::lastInsertId() and returns the value generated
     * by the database for an auto-increment column or sequence in the current session.
     *
     * The returned value is driver-dependent and will be a string if supported,
     * or false if no value is available.
     *
     * @return string|false The last inserted ID as a string, or false if not supported.
     */
    public static function getLastInsertedID(): string|false {
        return self::connect()->lastInsertId();
    }

    /**
     * Fetch multiple rows from a SELECT query.
     * Optionally extract a single column from each row.
     *
     * @param string      $sql    SQL SELECT query.
     * @param array       $params Parameters to bind (optional).
     * @param string|null $key    Optional column to extract.
     * @return array Array of rows or column values.
     */
    public static function select(string $sql, array $params = [], ?string $key = null): array {
        $pdo = self::connect();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        $rows = $stmt->fetchAll();

        // Return only the specified column if requested
        if ($key !== null) {
            return array_map(fn($row) => $row[$key] ?? null, $rows);
        }

        return $rows;
    }

    /**
     * Fetch a single row or a single value from a SELECT query.
     *
     * @param string      $sql    SQL SELECT query.
     * @param array       $params Parameters to bind (optional).
     * @param string|null $key    Optional column to extract.
     * @return mixed Array row, scalar value, or null.
     */
    public static function selectRow(string $sql, array $params = [], ?string $key = null): mixed {
        $pdo = self::connect();
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        $row = $stmt->fetch();

        if (!$row) return null;

        // Return single column value if key is defined
        if ($key !== null) {
            return $row[$key] ?? null;
        }

        return $row;
    }

    /**
     * Disconnect and release the PDO connection.
     *
     * Typically used during shutdown or test teardown.
     *
     * @return void
     */
    public static function disconnect(): void {
        self::$connection = null;
        self::$transactionLevel = 0;
    }
}
