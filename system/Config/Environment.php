<?php
namespace System\Config;

/**
 * Application environment resolver.
 *
 * Determines the current runtime environment (e.g., production, development, testing)
 * from the `APP_ENV` global configuration value.
 *
 * Provides helper methods to check the environment type.
 */
class Environment {
    /**
     * Cached environment value (lowercase).
     *
     * @var string|null
     */
    private static $env = null;

    /**
     * Get the current environment name.
     *
     * Reads from Globals::get('APP_ENV'), normalizes the value, and
     * falls back to 'production' if the value is missing or invalid.
     *
     * @return string One of: 'production', 'development', 'testing'.
     */
    public static function env(): string {
        if(!empty(self::$env)) {
            return self::$env;
        }

        // Allowed environments
        $permitted = [
            'production',
            'development',
            'testing',
        ];

        // Read and normalize from config
        self::$env = !empty(Globals::get('APP_ENV'))
            ? strtolower(Globals::get('APP_ENV'))
            : "";

        // Fallback to "production" if invalid
        if (!in_array(self::$env, $permitted)) {
            self::$env = "production";
        }
        return self::$env;
    }

    /**
     * Check if the current environment matches the given value.
     *
     * @param string $env Environment name to compare.
     * @return bool
     */
    public static function is(string $env): bool {
        return self::env() === $env;
    }

    /**
     * Check if the current environment is "production".
     *
     * @return bool
     */
    public static function isProduction(): bool {
        return self::env() === 'production';
    }

    /**
     * Check if the current environment is "development".
     *
     * @return bool
     */
    public static function isDevelopment(): bool {
        return self::env() === 'development';
    }

    /**
     * Check if the current environment is "testing".
     *
     * @return bool
     */
    public static function isTesting(): bool {
        return self::env() === 'testing';
    }
}
