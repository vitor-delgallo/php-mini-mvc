<?php

/**
 * @var League\Route\Router $router
 * The application's main router instance.
 * Responsible for dispatching HTTP requests to the appropriate route handler.
 */

/**
 * @var Psr\Http\Message\ServerRequestInterface $request
 * PSR-7 compliant HTTP request object containing all incoming request data.
 */

// Dispatch the incoming request using the configured router.
// This resolves the matched route and executes the corresponding handler,
// returning a PSR-7 compliant response object.
$response = $router->dispatch($request);