<?php
namespace System\Core;

use \System\Config\Globals;

/**
 * Path helper utility.
 *
 * Provides static methods to resolve absolute paths for key directories in the project
 * (such as app, views, system, storage, public, etc.), as well as generating base URLs
 * and site URLs for links and redirects.
 *
 * Filesystem paths use the platform separator, so the framework runs on Windows and Linux.
 * `siteURL()` only honours `X-Forwarded-Host` / `X-Forwarded-Proto` when the request comes
 * from an address listed in `TRUSTED_PROXIES`.
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
        return self::root() . DIRECTORY_SEPARATOR . 'app';
    }

    /**
     * Get the /app/Bootable directory path.
     *
     * @return string
     */
    public static function appBootable(): string {
        return self::app() . DIRECTORY_SEPARATOR . 'Bootable';
    }

    /**
     * Get the /app/helpers directory path.
     *
     * @return string
     */
    public static function appHelpers(): string {
        return self::app() . DIRECTORY_SEPARATOR . 'helpers';
    }

    /**
     * Get the /app/languages directory path.
     *
     * @return string
     */
    public static function appLanguages(): string {
        return self::app() . DIRECTORY_SEPARATOR . 'languages';
    }

    /**
     * Get the /app/routes directory path.
     *
     * @return string
     */
    public static function appRoutes(): string {
        return self::app() . DIRECTORY_SEPARATOR . 'routes';
    }

    /**
     * Get the /app/Middlewares directory path.
     *
     * @return string
     */
    public static function appMiddlewares(): string {
        return self::app() . DIRECTORY_SEPARATOR . 'Middlewares';
    }

    /**
     * Get the /app/Controllers directory path.
     *
     * @return string
     */
    public static function appControllers(): string {
        return self::app() . DIRECTORY_SEPARATOR . 'Controllers';
    }

    /**
     * Get the /app/Models directory path.
     *
     * @return string
     */
    public static function appModels(): string {
        return self::app() . DIRECTORY_SEPARATOR . 'Models';
    }

    /**
     * Get the /app/views directory path.
     *
     * @return string
     */
    public static function appViews(): string {
        return self::app() . DIRECTORY_SEPARATOR . 'views';
    }

    /**
     * Get the /app/views/pages directory path.
     *
     * @return string
     */
    public static function appViewsPages(): string {
        return self::appViews() . DIRECTORY_SEPARATOR . 'pages';
    }

    /**
     * Get the /app/views/templates directory path.
     *
     * @return string
     */
    public static function appViewsTemplates(): string {
        return self::appViews() . DIRECTORY_SEPARATOR . 'templates';
    }

    /**
     * Get the /system directory path.
     *
     * @return string
     */
    public static function system(): string {
        return self::root() . DIRECTORY_SEPARATOR . 'system';
    }

    /**
     * Get the /system/Bootable directory path.
     *
     * @return string
     */
    public static function systemInterfaces(): string {
        return self::system() . DIRECTORY_SEPARATOR . 'Interfaces';
    }

    /**
     * Get the /system/helpers directory path.
     *
     * @return string
     */
    public static function systemHelpers(): string {
        return self::system() . DIRECTORY_SEPARATOR . 'helpers';
    }

    /**
     * Get the /system/languages directory path.
     *
     * @return string
     */
    public static function systemLanguages(): string {
        return self::system() . DIRECTORY_SEPARATOR . 'languages';
    }

    /**
     * Get the /system/routes directory path.
     *
     * @return string
     */
    public static function systemRoutes(): string {
        return self::system() . DIRECTORY_SEPARATOR . 'routes';
    }

    /**
     * Get the /system/Middlewares directory path.
     *
     * @return string
     */
    public static function systemMiddlewares(): string {
        return self::system() . DIRECTORY_SEPARATOR . 'Middlewares';
    }

    /**
     * Get the /system/Controllers directory path.
     *
     * @return string
     */
    public static function systemControllers(): string {
        return self::system() . DIRECTORY_SEPARATOR . 'Controllers';
    }

    /**
     * Get the /system/Models directory path.
     *
     * @return string
     */
    public static function systemModels(): string {
        return self::system() . DIRECTORY_SEPARATOR . 'Models';
    }

    /**
     * Get the /system/views directory path.
     *
     * @return string
     */
    public static function systemViews(): string {
        return self::system() . DIRECTORY_SEPARATOR . 'views';
    }

    /**
     * Get the /system/views/pages directory path.
     *
     * @return string
     */
    public static function systemViewsPages(): string {
        return self::systemViews() . DIRECTORY_SEPARATOR . 'pages';
    }

    /**
     * Get the /system/views/templates directory path.
     *
     * @return string
     */
    public static function systemViewsTemplates(): string {
        return self::systemViews() . DIRECTORY_SEPARATOR . 'templates';
    }

    /**
     * Get the /system/includes directory path.
     *
     * @return string
     */
    public static function systemIncludes(): string {
        return self::system() . DIRECTORY_SEPARATOR . 'includes';
    }

    /**
     * Get the /public directory path.
     *
     * @return string
     */
    public static function public(): string {
        return self::root() . DIRECTORY_SEPARATOR . 'public';
    }

    /**
     * Get the /storage directory path.
     *
     * @return string
     */
    public static function storage(): string {
        return self::root() . DIRECTORY_SEPARATOR . 'storage';
    }

    /**
     * Get the /storage/sessions directory path.
     *
     * @return string
     */
    public static function storageSessions(): string {
        return self::storage() . DIRECTORY_SEPARATOR . 'sessions';
    }

    /**
     * Get the /storage/logs directory path.
     *
     * @return string
     */
    public static function storageLogs(): string {
        return self::storage() . DIRECTORY_SEPARATOR . 'logs';
    }

    /**
     * Get the legacy /languages directory path.
     *
     * Runtime translation loading uses appLanguages() and systemLanguages().
     *
     * @return string
     */
    public static function languages(): string {
        return self::root() . DIRECTORY_SEPARATOR . 'languages';
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
     * Get the application's base path (subdirectory) to public folder, as defined in environment config.
     * Normalizes slashes and prepends a single leading slash.
     * 
     * @return string Normalized base path with public directory (e.g. "/myapp/public" or "/public").
     */
    public static function basePathPublic(): string {
        return (self::basePath() . "/public");
    }

    /**
     * Generate the full site URL (e.g. "https://domain.com/base/path/")
     * with optional suffix.
     *
     * @param string|null $final Optional suffix (e.g. "dashboard" → "/dashboard")
     * @return string Full site URL.
     */
    public static function siteURL(?string $final = null): string {
        $trustProxy = self::isTrustedProxy();

        // HTTPS: the server's own flag, or the proxy's X-Forwarded-Proto when the proxy is trusted
        $https = isset($_SERVER['HTTPS']) && in_array(strtolower((string) $_SERVER['HTTPS']), ['on', '1'], true);
        if (!$https && $trustProxy) {
            $https = strtolower(trim((string) ($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? ''))) === 'https';
        }
        $protocol = $https ? 'https://' : 'http://';

        // Host: X-Forwarded-Host only from a trusted proxy (first entry when it carries a list)
        $forwardedHost = $trustProxy ? trim(explode(',', (string) ($_SERVER['HTTP_X_FORWARDED_HOST'] ?? ''))[0]) : '';
        $host = $forwardedHost !== ''
            ? $forwardedHost
            : ($_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? 'localhost');

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

    /**
     * Whether the connecting address is a trusted reverse proxy, according to `TRUSTED_PROXIES`:
     * a comma-separated list of IPs and IPv4/IPv6 CIDR blocks, or `*` to trust every connection
     * (only when the application is reachable exclusively through a proxy that rewrites the
     * X-Forwarded-* headers itself). Empty means no proxy is trusted.
     *
     * @param string|null $remoteAddr Address to check; defaults to REMOTE_ADDR.
     */
    public static function isTrustedProxy(?string $remoteAddr = null): bool {
        $remoteAddr = trim((string) ($remoteAddr ?? $_SERVER['REMOTE_ADDR'] ?? ''));
        $list = trim((string) (Globals::env('TRUSTED_PROXIES') ?? ''));

        if ($remoteAddr === '' || $list === '') {
            return false;
        }

        foreach (preg_split('/\s*,\s*/', $list) ?: [] as $entry) {
            if ($entry === '') {
                continue;
            }
            if ($entry === '*') {
                return true;
            }
            if (str_contains($entry, '/')) {
                if (self::ipInCidr($remoteAddr, $entry)) {
                    return true;
                }
            } elseif (strcasecmp($entry, $remoteAddr) === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Checks an IP against a CIDR block (IPv4 or IPv6).
     */
    private static function ipInCidr(string $ip, string $cidr): bool {
        [$subnet, $bits] = array_pad(explode('/', $cidr, 2), 2, '');

        if (
            filter_var($ip, FILTER_VALIDATE_IP) === false ||
            filter_var($subnet, FILTER_VALIDATE_IP) === false ||
            !ctype_digit($bits)
        ) {
            return false;
        }

        $ipBin = inet_pton($ip);
        $subnetBin = inet_pton($subnet);
        if ($ipBin === false || $subnetBin === false || strlen($ipBin) !== strlen($subnetBin)) {
            return false;
        }

        $bits = (int) $bits;
        $maxBits = strlen($ipBin) * 8;
        if ($bits > $maxBits) {
            return false;
        }

        $fullBytes = intdiv($bits, 8);
        $remainder = $bits % 8;

        if ($fullBytes > 0 && substr($ipBin, 0, $fullBytes) !== substr($subnetBin, 0, $fullBytes)) {
            return false;
        }
        if ($remainder === 0) {
            return true;
        }

        $mask = (0xFF << (8 - $remainder)) & 0xFF;

        return ((ord($ipBin[$fullBytes]) ^ ord($subnetBin[$fullBytes])) & $mask) === 0;
    }
}
