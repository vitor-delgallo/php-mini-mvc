<?php

namespace System\Core;

use \System\Config\Session AS ConfigSession;

/**
 * Session management utility class.
 *
 * Provides a static interface to start, read, write, and destroy session data,
 * abstracting session operations in a centralized way.
 */
class Session {
    /**
     * Ensures the session is started.
     *
     * Throws an exception if session driver is explicitly set to "none".
     */
    public static function start(): void {
        if(ConfigSession::isNone()) {
            throw new \RuntimeException(Language::get("session.driver.not-found"));
        }

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Check if a session variable exists.
     *
     * @param string $key Variable name
     * @return bool True if exists, false otherwise
     */
    public static function has(string $key): bool {
        self::start();
        return isset($_SESSION[$key]);
    }

    /**
     * Retrieve a session variable.
     *
     * @param string $key     Variable name
     * @param mixed  $default Default value if key does not exist
     * @return mixed
     */
    public static function get(string $key, mixed $default = null): mixed {
        self::start();
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Set a session variable.
     *
     * @param string $key   Variable name
     * @param mixed  $value Value to store
     */
    public static function set(string $key, mixed $value): void {
        self::start();
        $_SESSION[$key] = $value;
    }

    /**
     * Set multiple session variables at once.
     *
     * @param array $items Associative array of key => value
     */
    public static function setMany(array $items): void {
        self::start();
        foreach ($items as $key => $value) {
            $_SESSION[$key] = $value;
        }
    }

    /**
     * Remove a session variable.
     *
     * @param string $key Variable name
     */
    public static function forget(string $key): void {
        self::start();
        unset($_SESSION[$key]);
    }

    /**
     * Clear all session data (but keeps the session alive).
     */
    public static function clear(): void {
        self::start();
        $_SESSION = [];
    }

    /**
     * Save and close the current session.
     *
     * Useful when you want to release the session lock manually.
     */
    public static function save(): void {
        if (session_status() === PHP_SESSION_ACTIVE) {
            session_write_close();
        }
        self::start();
    }

    /**
     * Destroy the current session and remove session cookie.
     */
    public static function destroy(): void {
        if (session_status() === PHP_SESSION_ACTIVE) {
            $_SESSION = [];
            session_unset();
            session_destroy();
        }
    }

    /**
     * Regenerate the session ID.
     *
     * @param bool $deleteOldSession Whether to delete the old session or not
     */
    public static function regenerate(bool $deleteOldSession = true): void {
        self::start();
        session_regenerate_id($deleteOldSession);
    }
}
