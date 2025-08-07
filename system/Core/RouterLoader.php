<?php

namespace System\Core;

use League\Route\Router;
use League\Route\RouteGroup;
use Laminas\Diactoros\ServerRequestFactory;
use Psr\Http\Message\ResponseInterface;

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

        self::$router = new Router();
    }

    /**
     * Loads a route file with access to the global router ($router).
     *
     * @param string $file Path relative to the routes directory (e.g. 'web' or 'api.php')
     */
    public static function load(string $file): void {
        self::startRouter();
        $router = self::$router;

        // Normalize the file path
        $file = rtrim($file, "/");
        $file = rtrim($file, "\\");
        if (str_ends_with($file, ".php")) {
            $file = substr($file, 0, -4);
        }

        // Makes $router available inside the route file
        include_once Path::appRoutes() . "/" . $file . ".php";

        self::$router = $router;
    }

    /**
     * Carrega um arquivo de rotas dentro de um grupo com prefixo.
     *
     * O arquivo de rota continua usando $router normalmente.
     *
     * @param string $prefix Prefixo de grupo (ex: /api)
     * @param string $file Caminho do arquivo de rota
     */
    public static function loadWithPrefix(string $prefix, string $file): void {
        self::startRouter();

        // Normalize the file path
        $file = rtrim($file, "/");
        $file = rtrim($file, "\\");
        if (str_ends_with($file, ".php")) {
            $file = substr($file, 0, -4);
        }

        $router = self::$router;

        // Creates a group with prefix and exposes it as $router
        $router->group($prefix, function (RouteGroup $group) use ($file) {
            $router = $group; // shadow $router inside scope
            include_once Path::appRoutes() . "/" . $file . ".php";
        });

        self::$router = $router;
    }

    /**
     * Dispatches the current HTTP request using the router.
     *
     * - Builds the PSR-7 ServerRequest from PHP globals
     * - Removes base path from URI if configured
     * - Resolves the route and returns a PSR-7 Response
     *
     * @return ResponseInterface The HTTP response from the matched route handler
     */
    public static function dispatch(): ResponseInterface {
        self::startRouter();

        // Build a PSR-7 request from PHP superglobals
        $request = ServerRequestFactory::fromGlobals();

        /**
         * Normalize the request URI path by removing the base path prefix (if defined).
         *
         * Useful when the app is hosted in a subdirectory and routes should be matched
         * relative to the application root.
         */
        if (!empty(Path::basePath())) {
            $uri = $request->getUri();

            // Remove the base path from the beginning of the URI path
            $cleanedPath = preg_replace('#^' . preg_quote(Path::basePath(), '#') . '#', '', $uri->getPath());

            // Update the request with the cleaned URI
            $uri = $uri->withPath($cleanedPath);
            $request = $request->withUri($uri);
        }

        // Dispatch the incoming request using the configured router.
        // This resolves the matched route and executes the corresponding handler,
        // returning a PSR-7 compliant response object.
        return self::$router->dispatch($request);
    }
}