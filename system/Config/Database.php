<?php
namespace System\Config;

use System\Core\Language;

/**
 * Database configuration resolver.
 *
 * Resolves the default database connection from unsuffixed DB_* variables and
 * named connections from suffixed DB_*_<NAME> variables.
 */
class Database {
    public const DEFAULT_CONNECTION = 'default';
    public const DEFAULT_CHARSET = 'utf8';

    /**
     * Runtime connection configs registered by code.
     *
     * @var array<string, array<string, mixed>>
     */
    private static array $runtimeConnections = [];

    /**
     * Supported PDO drivers.
     *
     * @return array<int, string>
     */
    public static function permittedDrivers(): array {
        return ['mysql', 'pgsql'];
    }

    /**
     * Normalize a connection name to its registry key.
     *
     * @param string|null $connection
     * @return string
     */
    public static function normalizeConnectionName(?string $connection = null): string {
        $connection = strtolower(trim((string) $connection));

        return $connection === '' ? self::DEFAULT_CONNECTION : $connection;
    }

    /**
     * Get the default port for a supported database driver.
     *
     * @param string $driver
     * @return int|null
     */
    public static function defaultPort(string $driver): ?int {
        return match (strtolower($driver)) {
            'mysql' => 3306,
            'pgsql' => 5432,
            default => null,
        };
    }

    /**
     * Get one normalized database config.
     *
     * @param string $connection
     * @return array<string, mixed>|null
     */
    public static function connection(string $connection = self::DEFAULT_CONNECTION): ?array {
        $connection = self::normalizeConnectionName($connection);

        if (isset(self::$runtimeConnections[$connection])) {
            return self::$runtimeConnections[$connection];
        }

        if ($connection === self::DEFAULT_CONNECTION) {
            return self::defaultConnectionFromEnv();
        }

        return self::namedConnectionFromEnv($connection);
    }

    /**
     * Get all configured database connections.
     *
     * @return array<string, array<string, mixed>>
     */
    public static function connections(): array {
        $connections = [];

        $default = self::defaultConnectionFromEnv();
        if ($default !== null) {
            $connections[self::DEFAULT_CONNECTION] = $default;
        }

        foreach (self::envConnectionNames() as $name) {
            $config = self::namedConnectionFromEnv($name);
            if ($config !== null) {
                $connections[$name] = $config;
            }
        }

        foreach (self::$runtimeConnections as $name => $config) {
            $connections[$name] = $config;
        }

        return $connections;
    }

    /**
     * Get configured connection names.
     *
     * @return array<int, string>
     */
    public static function connectionNames(): array {
        return array_keys(self::connections());
    }

    /**
     * Check whether a connection is configured.
     *
     * @param string $connection
     * @return bool
     */
    public static function hasConnection(string $connection): bool {
        return self::connection($connection) !== null;
    }

    /**
     * Register or replace a runtime connection config.
     *
     * @param string $connection
     * @param array<string, mixed> $config
     * @return void
     */
    public static function configure(string $connection, array $config): void {
        $connection = self::normalizeConnectionName($connection);
        $normalized = self::normalizeConfig($config, $connection, false);

        if ($normalized === null) {
            throw new \RuntimeException(
                Language::get('system.database.connection.not-found.info', ['connection' => $connection])
            );
        }

        $normalized['source'] = 'runtime';
        self::$runtimeConnections[$connection] = $normalized;
    }

    /**
     * Remove one runtime connection config.
     *
     * @param string $connection
     * @return void
     */
    public static function forgetConnection(string $connection): void {
        unset(self::$runtimeConnections[self::normalizeConnectionName($connection)]);
    }

    /**
     * Get the configured database driver for one connection.
     *
     * @param string $connection
     * @return string
     */
    public static function env(string $connection = self::DEFAULT_CONNECTION): string {
        $config = self::connection($connection);

        return $config['driver'] ?? 'none';
    }

    /**
     * Check if a connection driver matches a given value.
     *
     * @param string $env
     * @param string $connection
     * @return bool
     */
    public static function is(string $env, string $connection = self::DEFAULT_CONNECTION): bool {
        return self::env($connection) === strtolower($env);
    }

    /**
     * Check if a connection is MySQL.
     *
     * @param string $connection
     * @return bool
     */
    public static function isMysql(string $connection = self::DEFAULT_CONNECTION): bool {
        return self::env($connection) === 'mysql';
    }

    /**
     * Check if a connection is PostgreSQL.
     *
     * @param string $connection
     * @return bool
     */
    public static function isPostgres(string $connection = self::DEFAULT_CONNECTION): bool {
        return self::env($connection) === 'pgsql';
    }

    /**
     * Check if no valid database driver is configured for a connection.
     *
     * @param string $connection
     * @return bool
     */
    public static function isNone(string $connection = self::DEFAULT_CONNECTION): bool {
        return self::env($connection) === 'none';
    }

    /**
     * Resolve the default unsuffixed DB_* config.
     *
     * @return array<string, mixed>|null
     */
    private static function defaultConnectionFromEnv(): ?array {
        $config = [
            'driver' => Globals::env('DB_DRIVER'),
            'host' => Globals::env('DB_HOST'),
            'port' => Globals::env('DB_PORT'),
            'name' => Globals::env('DB_NAME'),
            'user' => Globals::env('DB_USER'),
            'pass' => Globals::env('DB_PASS'),
            'charset' => Globals::env('DB_CHARSET'),
        ];

        $normalized = self::normalizeConfig($config, self::DEFAULT_CONNECTION, false, false);
        if ($normalized !== null) {
            $normalized['source'] = 'env';
        }

        return $normalized;
    }

    /**
     * Resolve a suffixed DB_*_<NAME> config.
     *
     * @param string $connection
     * @return array<string, mixed>|null
     */
    private static function namedConnectionFromEnv(string $connection): ?array {
        $connection = self::normalizeConnectionName($connection);
        if ($connection === self::DEFAULT_CONNECTION) {
            return self::defaultConnectionFromEnv();
        }

        $suffix = strtoupper($connection);
        $config = [
            'driver' => Globals::env("DB_DRIVER_{$suffix}"),
            'host' => Globals::env("DB_HOST_{$suffix}"),
            'port' => Globals::env("DB_PORT_{$suffix}"),
            'name' => Globals::env("DB_NAME_{$suffix}"),
            'user' => Globals::env("DB_USER_{$suffix}"),
            'pass' => Globals::env("DB_PASS_{$suffix}"),
            'charset' => Globals::env("DB_CHARSET_{$suffix}"),
        ];

        $normalized = self::normalizeConfig($config, $connection, true, true);
        if ($normalized !== null) {
            $normalized['source'] = 'env';
        }

        return $normalized;
    }

    /**
     * Discover suffixed DB_*_<NAME> groups from loaded env values.
     *
     * @return array<int, string>
     */
    private static function envConnectionNames(): array {
        $env = Globals::env();
        if (!is_array($env)) {
            return [];
        }

        $names = [];
        foreach (array_keys($env) as $key) {
            if (preg_match('/^DB_(?:DRIVER|HOST|PORT|NAME|USER|PASS|CHARSET)_([A-Z0-9_]+)$/', (string) $key, $matches)) {
                $name = self::normalizeConnectionName($matches[1]);
                if ($name !== self::DEFAULT_CONNECTION) {
                    $names[$name] = $name;
                }
            }
        }

        ksort($names);
        return array_values($names);
    }

    /**
     * Normalize and validate a connection config.
     *
     * @param array<string, mixed> $config
     * @param string $connection
     * @param bool $ignoreIncomplete
     * @param bool $throwUnsupportedWhenComplete
     * @return array<string, mixed>|null
     */
    private static function normalizeConfig(
        array $config,
        string $connection,
        bool $ignoreIncomplete,
        bool $throwUnsupportedWhenComplete = true
    ): ?array {
        $connection = self::normalizeConnectionName($connection);
        $driver = strtolower(trim((string) self::readConfigValue($config, 'driver', 'DB_DRIVER')));

        if ($driver === '' || $driver === 'none') {
            return null;
        }

        $normalized = [
            'connection' => $connection,
            'driver' => $driver,
            'host' => trim((string) self::readConfigValue($config, 'host', 'DB_HOST')),
            'port' => self::readConfigValue($config, 'port', 'DB_PORT'),
            'name' => trim((string) self::readConfigValue($config, 'name', 'DB_NAME')),
            'user' => trim((string) self::readConfigValue($config, 'user', 'DB_USER')),
            'pass' => (string) (self::readConfigValue($config, 'pass', 'password', 'DB_PASS') ?? ''),
            'charset' => trim((string) (self::readConfigValue($config, 'charset', 'DB_CHARSET') ?? self::DEFAULT_CHARSET)),
        ];

        $missing = self::missingRequiredParameters($normalized);
        if (!empty($missing)) {
            if ($ignoreIncomplete) {
                return null;
            }

            throw new \RuntimeException(Language::get('system.database.connection.incomplete.info', [
                'connection' => $connection,
                'parameters' => implode(', ', $missing),
            ]));
        }

        if (!in_array($driver, self::permittedDrivers(), true)) {
            if ($throwUnsupportedWhenComplete) {
                throw new \RuntimeException(Language::get('system.database.driver.not-supported.info', ['driver' => $driver]));
            }

            return null;
        }

        if (empty($normalized['port'])) {
            $normalized['port'] = self::defaultPort($driver);
        }

        if (empty($normalized['charset'])) {
            $normalized['charset'] = self::DEFAULT_CHARSET;
        }

        return $normalized;
    }

    /**
     * Read a config value from possible key aliases.
     *
     * @param array<string, mixed> $config
     * @param string ...$keys
     * @return mixed
     */
    private static function readConfigValue(array $config, string ...$keys): mixed {
        foreach ($keys as $key) {
            if (array_key_exists($key, $config)) {
                return $config[$key];
            }
        }

        return null;
    }

    /**
     * Return missing required config fields.
     *
     * @param array<string, mixed> $config
     * @return array<int, string>
     */
    private static function missingRequiredParameters(array $config): array {
        $required = ['driver', 'host', 'name', 'user'];
        $missing = [];

        foreach ($required as $key) {
            if (empty($config[$key])) {
                $missing[] = $key;
            }
        }

        return $missing;
    }
}
