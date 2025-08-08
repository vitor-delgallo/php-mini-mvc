<?php
namespace System\Core;

use \System\Config\Globals;

/**
 * Path helper utility.
 *
 * Provides static methods to resolve absolute paths for key directories in the project
 * (such as app, views, system, storage, public, etc.), as well as generating base URLs
 * and site URLs for links and redirects.
 */
class Path {
    /**
     * Cached result of the base path, computed only once.
     *
     * Used to avoid recalculating the base path logic on every call to basePath().
     * It is derived from Globals::env('BASE_PATH') and normalized.
     *
     * Example: if BASE_PATH = "myapp/", the final result will be "/myapp"
     *
     * @var string|null
     */
    private static ?string $basePathCache = null;

    /**
     * Get the root directory of the project.
     *
     * @return string Absolute path to the project root.
     */
    public static function root(): string {
        return dirname(__DIR__, 2);
    }

    /**
     * Get the /app directory path.
     *
     * @return string
     */
    public static function app(): string {
        return self::root() . '\\app';
    }

    /**
     * Get the /app/Bootable directory path.
     *
     * @return string
     */
    public static function appBootable(): string {
        return self::app() . '\\Bootable';
    }

    /**
     * Get the /app/helpers directory path.
     *
     * @return string
     */
    public static function appHelpers(): string {
        return self::app() . '\\helpers';
    }

    /**
     * Get the /app/routes directory path.
     *
     * @return string
     */
    public static function appRoutes(): string {
        return self::app() . '\\routes';
    }

    /**
     * Get the /app/Middlewares directory path.
     *
     * @return string
     */
    public static function appMiddlewares(): string {
        return self::app() . '\\Middlewares';
    }

    /**
     * Get the /app/Controllers directory path.
     *
     * @return string
     */
    public static function appControllers(): string {
        return self::app() . '\\Controllers';
    }

    /**
     * Get the /app/Models directory path.
     *
     * @return string
     */
    public static function appModels(): string {
        return self::app() . '\\Models';
    }

    /**
     * Get the /app/views directory path.
     *
     * @return string
     */
    public static function appViews(): string {
        return self::app() . '\\views';
    }

    /**
     * Get the /app/views/pages directory path.
     *
     * @return string
     */
    public static function appViewsPages(): string {
        return self::appViews() . '\\pages';
    }

    /**
     * Get the /app/views/templates directory path.
     *
     * @return string
     */
    public static function appViewsTemplates(): string {
        return self::appViews() . '\\templates';
    }

    /**
     * Get the /system directory path.
     *
     * @return string
     */
    public static function system(): string {
        return self::root() . '\\system';
    }

    /**
     * Get the /system/Bootable directory path.
     *
     * @return string
     */
    public static function systemInterfaces(): string {
        return self::system() . '\\Interfaces';
    }

    /**
     * Get the /system/helpers directory path.
     *
     * @return string
     */
    public static function systemHelpers(): string {
        return self::system() . '\\helpers';
    }

    /**
     * Get the /system/includes directory path.
     *
     * @return string
     */
    public static function systemIncludes(): string {
        return self::system() . '\\includes';
    }

    /**
     * Get the /public directory path.
     *
     * @return string
     */
    public static function public(): string {
        return self::root() . '\\public';
    }

    /**
     * Get the /storage directory path.
     *
     * @return string
     */
    public static function storage(): string {
        return self::root() . '\\storage';
    }

    /**
     * Get the /storage/sessions directory path.
     *
     * @return string
     */
    public static function storageSessions(): string {
        return self::storage() . '\\sessions';
    }

    /**
     * Get the /storage/logs directory path.
     *
     * @return string
     */
    public static function storageLogs(): string {
        return self::storage() . '\\logs';
    }

    /**
     * Get the /languages directory path.
     *
     * @return string
     */
    public static function languages(): string {
        return self::root() . '\\languages';
    }

    /**
     * Get the application's base path (subdirectory), as defined in environment config.
     * Normalizes slashes and prepends a single leading slash.
     *
     * @return string Normalized base path (e.g. "/myapp" or "").
     */
    public static function basePath(): string {
        if(self::$basePathCache !== null) {
            return self::$basePathCache;
        }

        self::$basePathCache = Globals::env('BASE_PATH') ?? "";

        // Remove trailing slashes
        self::$basePathCache = rtrim(self::$basePathCache, "/");
        self::$basePathCache = rtrim(self::$basePathCache, "\\");

        // Remove leading slashes
        self::$basePathCache = ltrim(self::$basePathCache, "/");
        self::$basePathCache = ltrim(self::$basePathCache, "\\");

        // Normalize to forward slashes
        self::$basePathCache = str_replace("\\", "/", self::$basePathCache);

        // Ensure "/" at the start of the string
        if(!empty(self::$basePathCache)) {
            self::$basePathCache = ("/" . self::$basePathCache);
        }
        return self::$basePathCache;
    }

    /**
     * Generate the full site URL (e.g. "https://domain.com/base/path/")
     * with optional suffix.
     *
     * @param string|null $final Optional suffix (e.g. "dashboard" → "/dashboard")
     * @return string Full site URL.
     */
    public static function siteURL(?string $final = null): string {
        $protocol = 'http://';

        // Determine if HTTPS is enabled (various proxy-aware checks)
        if (
            isset($_SERVER['HTTPS']) && ($_SERVER['HTTPS'] == 'on' || $_SERVER['HTTPS'] == 1) ||
            isset($_SERVER['HTTP_X_FORWARDED_PROTO']) &&
            $_SERVER['HTTP_X_FORWARDED_PROTO'] == 'https' &&
            isset($_SERVER['REMOTE_ADDR'])
        ) {
            $protocol = 'https://';
        }

        // Determine the current host
        $host = $_SERVER['HTTP_X_FORWARDED_HOST']
            ?? $_SERVER['HTTP_HOST']
            ?? $_SERVER['SERVER_NAME']
            ?? 'localhost';

        // Build base URL
        $ret = $protocol . $host . self::basePath();
        $ret = str_replace("\\", "/", $ret);

        // Ensure trailing slash
        if (mb_substr($ret, -1) !== "/") {
            $ret .= "/";
        }

        // Optionally append suffix
        if (!empty($final)) {
            $final = str_replace("\\", "/", $final);
            if (mb_substr($final, 0, 1) === "/") {
                $final = mb_substr($final, 1);
            }
        }

        return $ret . $final;
    }
}