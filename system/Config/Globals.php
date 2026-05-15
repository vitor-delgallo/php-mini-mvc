<?php
namespace System\Config;

use Dotenv\Dotenv;
use System\Core\Path;

/**
 * Global configuration manager.
 *
 * Provides a static interface to store, retrieve, and manipulate configuration values
 * loaded from a `.env` file and at runtime.
 *
 * Acts as an internal registry used throughout the application.
 */
class Globals {
    /**
     * Internal key-value store for all global configuration.
     *
     * @var array<string, mixed>
     */
    private static array $config = [];

    /**
     * Internal key-value store for all env configuration.
     *
     * @var null|array<string, mixed>
     */
    private static ?array $env = null;

    /**
     * Retrieve a config value by key, or get the full array.
     *
     * @param string|null $key Optional key to retrieve. If null, returns all config.
     * @param mixed $default Default value for retrieve the key.
     * @return mixed
     */
    public static function get(?string $key = null, mixed $default = null): mixed {
        if ($key === null) {
            return self::$config;
        }

        return self::$config[$key] ?? $default;
    }

    /**
     * Add or overwrite a single configuration key.
     *
     * @param string $key   Configuration key.
     * @param mixed  $value Value to set.
     * @return void
     */
    public static function add(string $key, mixed $value): void {
        self::$config[$key] = $value;
    }

    /**
     * Merge multiple key-value pairs into the global configuration.
     *
     * @param array $config Associative array of configurations to merge.
     * @return void
     */
    public static function merge(array $config): void {
        self::$config = array_merge(self::$config, $config);
    }

    /**
     * Remove a single configuration key.
     *
     * @param string $key Key to remove.
     * @return void
     */
    public static function forget(string $key): void {
        unset(self::$config[$key]);
    }

    /**
     * Remove multiple configuration keys.
     *
     * @param array $keys List of keys to remove.
     * @return void
     */
    public static function forgetMany(array $keys): void {
        foreach ($keys as $key) {
            self::forget($key);
        }
    }

    /**
     * Reset the entire configuration store and reload .env variables.
     *
     * @return void
     */
    public static function reset(): void {
        self::$config = [];
        self::$env = null;
        
        self::loadEnv();
    }

    /**
     * Load environment variables from a `.env` file and add them to the env store.
     *
     * Uses the `vlucas/phpdotenv` package to load values into $_ENV,
     * and then merges them into the internal env array.
     *
     * @return void
     */
    public static function loadEnv(): void {
        if(self::$env !== null) {
            return;
        }

        try {
            $dotenv = Dotenv::createImmutable(Path::root());
            $dotenv->load();

            self::$env = array_merge([], $_ENV);
        } catch (\Throwable $ex) {}
    }

    /**
     * Retrieve an env value by key, or get the full array.
     *
     * @param string|null $key Optional key to retrieve. If null, returns all env.
     * @return array|string|null
     */
    public static function env(?string $key = null): array|string|null {
        self::loadEnv();

        if ($key === null) {
            return self::$env;
        }
        return self::$env[$key] ?? null;
    }

    /**
     * Gets the API prefix used to determine if a request is for an API route.
     * This is used to conditionally disable session handling for API requests.
     * 
     * @return string
     */
    public static function getApiPrefix(): string {
        return "/api";
    }

    /**
     * Gets the system web prefix used to determine if a request is for a system web route.
     *
     * @return string
     */
    public static function getSystemWebPrefix(): string {
        return "/web-system";
    }

    /**
     * Gets the system API prefix used to determine if a request is for a system API route.
     *
     * @return string
     */
    public static function getSystemApiPrefix(): string {
        return "/api-system";
    }

    /**
     * Checks if the current HTTP request is targeting the given prefix.
     *
     * @param string $prefix
     * @return bool
     */
    private static function isRequestForPrefix(string $prefix): bool {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        $base = self::env('BASE_PATH') ?? '';
        $cleanUri = str_replace($base, '', $uri);

        return !!preg_match('#^' . preg_quote($prefix) . '(/|$)#', $cleanUri);
    }

    /**
     * Checks if the current HTTP request is targeting an API route based on the URI and configured API prefix.
     * This is used to conditionally disable session handling for API requests.
     * 
     * @return bool
     */
    public static function isApiRequest(): bool {
        return self::isRequestForPrefix(self::getApiPrefix());
    }

    /**
     * Checks if the current HTTP request is targeting a system web route.
     *
     * @return bool
     */
    public static function isSystemWebRequest(): bool {
        return self::isRequestForPrefix(self::getSystemWebPrefix());
    }

    /**
     * Checks if the current HTTP request is targeting a system API route.
     *
     * @return bool
     */
    public static function isSystemApiRequest(): bool {
        return self::isRequestForPrefix(self::getSystemApiPrefix());
    }
}
