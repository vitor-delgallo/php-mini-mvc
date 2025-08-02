<?php
namespace System\Config;

/**
 * Database driver resolver.
 *
 * Determines which database driver is configured for the application
 * (e.g., MySQL or PostgreSQL), and provides helper methods to check
 * the active driver.
 *
 * Reads the value from Globals::get('DB_DRIVER') and validates it.
 * If invalid or missing, defaults to "none".
 */
class Database {
    /**
     * Cached database driver name (e.g., 'mysql', 'pgsql', 'none').
     *
     * @var string|null
     */
    private static $env = null;

    /**
     * Get the current database driver from environment configuration.
     *
     * Supported values:
     * - 'mysql'
     * - 'pgsql'
     * - 'none' (fallback)
     *
     * @return string
     */
    public static function env(): string {
        if(!empty(self::$env)) {
            return self::$env;
        }

        // Allowed database drivers
        $permitted = [
            'mysql',
            'pgsql',
        ];

        // Load and normalize driver name from environment
        self::$env = !empty(Globals::get('DB_DRIVER'))
            ? strtolower(Globals::get('DB_DRIVER'))
            : "";

        // Fallback to "none" if not valid
        if (!in_array(self::$env, $permitted)) {
            self::$env = "none";
        }
        return self::$env;
    }

    /**
     * Check if the current driver matches a given value.
     *
     * @param string $env The driver name to compare.
     * @return bool
     */
    public static function is(string $env): bool {
        return self::env() === $env;
    }

    /**
     * Check if the current driver is MySQL.
     *
     * @return bool
     */
    public static function isMysql(): bool {
        return self::env() === 'mysql';
    }

    /**
     * Check if the current driver is PostgreSQL.
     *
     * @return bool
     */
    public static function isPostgres(): bool {
        return self::env() === 'pgsql';
    }

    /**
     * Check if no valid driver is configured.
     *
     * @return bool
     */
    public static function isNone(): bool {
        return self::env() === 'none';
    }
}
