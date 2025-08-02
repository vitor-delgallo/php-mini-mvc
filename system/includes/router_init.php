<?php

use Laminas\Diactoros\ServerRequestFactory;
use League\Route\Router;
use System\Core\Path;

/**
 * Create a PSR-7 compatible ServerRequest object based on PHP superglobals.
 *
 * This includes:
 * - $_GET
 * - $_POST
 * - $_FILES
 * - $_COOKIE
 * - $_SERVER
 */
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

/**
 * Instantiate the application's main router.
 *
 * This will be responsible for handling route registration and dispatching requests.
 */
$router = new Router();