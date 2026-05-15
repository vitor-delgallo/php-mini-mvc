<?php

namespace System\Core;

use MiladRahimi\PhpRouter\Router;
use Laminas\Diactoros\ServerRequestFactory;
use \Throwable;

/**
 * Class RouterLoader
 *
 * A static utility for managing the routing system of the application.
 *
 * Features:
 * - Initializes a shared Router instance
 * - Loads route files with or without path prefix
 * - Handles route dispatch with PSR-7 ServerRequest
 * - Automatically strips base path from the URI
 */
class RouterLoader {
    /**
     * Holds the global Router instance shared across the app.
     */
    private static Router $router;

    /**
     * Initializes the router if not already started.
     */
    private static function startRouter(): void {
        if(!empty(self::$router)) {
            return;
        }

        self::$router = Router::create();
    }

    /**
     * Normalizes route filenames accepted by the loader.
     *
     * @param string $file Path relative to a routes directory.
     * @return string
     */
    private static function normalizeRouteFile(string $file): string {
        $file = rtrim($file, "/");
        $file = rtrim($file, "\\");
        if (str_ends_with($file, ".php")) {
            $file = substr($file, 0, -4);
        }

        return $file;
    }

    /**
     * Loads a route file from the given directory with access to the global router ($router).
     *
     * @param string $routesPath Absolute path to the routes directory.
     * @param string $file Path relative to the routes directory.
     */
    private static function loadFrom(string $routesPath, string $file): void {
        self::startRouter();
        $router = self::$router;
        $file = self::normalizeRouteFile($file);

        // Makes $router available inside the route file
        if(!empty(Path::basePath())) {
            $router->group(['prefix' => Path::basePath()], function (Router $group) use ($routesPath, $file) {
                $router = $group; // shadow $router inside scope
                include_once $routesPath . "/" . $file . ".php";
            });
        } else {
            include_once $routesPath . "/" . $file . ".php";
        }

        self::$router = $router;
    }

    /**
     * Loads a route file from the given directory inside a group prefix.
     *
     * @param string $routesPath Absolute path to the routes directory.
     * @param string $prefix Group prefix (e.g. /api).
     * @param string $file Path relative to the routes directory.
     */
    private static function loadFromWithPrefix(string $routesPath, string $prefix, string $file): void {
        self::startRouter();
        $file = self::normalizeRouteFile($file);
        $main = self::$router;

        $prefix = rtrim($prefix, "/");
        $prefix = ltrim($prefix, "/");
        $prefix = "/" . $prefix;

        // Creates a group with prefix and exposes it as $router
        $main->group(['prefix' => Path::basePath() . $prefix], function (Router $router) use ($routesPath, $file) {
            include_once $routesPath . "/" . $file . ".php";
        });

        self::$router = $main;
    }

    /**
     * Loads a route file with access to the global router ($router).
     *
     * @param string $file Path relative to the routes directory (e.g. 'web' or 'api.php')
     */
    public static function load(string $file): void {
        self::loadFrom(Path::appRoutes(), $file);
    }

    /**
     * Loads a route file inside a group with access to the global router ($router).
     *
     * @param string $prefix Group prefix (e.g. /api)
     * @param string $file Path relative to the routes directory (e.g. 'web' or 'api.php')
     */
    public static function loadWithPrefix(string $prefix, string $file): void {
        self::loadFromWithPrefix(Path::appRoutes(), $prefix, $file);
    }

    /**
     * Loads a system route file with access to the global router ($router).
     *
     * @param string $file Path relative to system/routes (e.g. 'web' or 'api.php')
     */
    public static function loadSystem(string $file): void {
        self::loadFrom(Path::systemRoutes(), $file);
    }

    /**
     * Loads a system route file inside a group with access to the global router ($router).
     *
     * @param string $prefix Group prefix (e.g. /web-system)
     * @param string $file Path relative to system/routes (e.g. 'web' or 'api.php')
     */
    public static function loadSystemWithPrefix(string $prefix, string $file): void {
        self::loadFromWithPrefix(Path::systemRoutes(), $prefix, $file);
    }

    /**
     * Dispatches the current HTTP request using the router.
     *
     * - Builds the PSR-7 ServerRequest from PHP globals
     * - Removes base path from URI if configured
     * - Resolves the route and returns a PSR-7 Response
     *
     * @throws Throwable
     */
    public static function dispatch(): void {
        self::startRouter();
        self::$router->dispatch();
    }
}
