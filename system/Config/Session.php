<?php
namespace System\Config;

/**
 * Session configuration resolver.
 *
 * Reads and validates the session driver from environment variables
 * (via Globals), and provides helper methods to check the selected driver.
 *
 * Supported drivers:
 * - files
 * - db
 * - none (fallback/default)
 */
class Session {
    /**
     * Cached session driver value (e.g., 'files', 'db', 'none').
     *
     * @var string|null
     */
    private static $env = null;

    /**
     * Resolve the session driver from environment settings.
     *
     * Falls back to "none" if value is missing or invalid.
     *
     * @return string One of: 'files', 'db', or 'none'.
     */
    public static function env(): string {
        if(!empty(self::$env)) {
            return self::$env;
        }

        // Allowed session drivers
        $permitted = [
            'files',
            'db',
        ];

        // Fetch and normalize SESSION_DRIVER from environment
        self::$env = !empty(Globals::get('SESSION_DRIVER'))
            ? strtolower(Globals::get('SESSION_DRIVER'))
            : "";

        // Fallback to "none" if invalid
        if(!in_array(self::$env, $permitted)) {
            self::$env = "none";
        }
        return self::$env;
    }

    /**
     * Check if the current session driver matches the given value.
     *
     * @param string $env Driver name to compare.
     * @return bool
     */
    public static function is(string $env): bool {
        return self::env() === $env;
    }

    /**
     * Check if the session driver is set to "files".
     *
     * @return bool
     */
    public static function isFiles(): bool {
        return self::env() === 'files';
    }

    /**
     * Check if the session driver is set to "db".
     *
     * @return bool
     */
    public static function isDB(): bool {
        return self::env() === 'db';
    }

    /**
     * Check if the session driver is "none" (disabled or invalid).
     *
     * @return bool
     */
    public static function isNone(): bool {
        return self::env() === 'none';
    }
}
