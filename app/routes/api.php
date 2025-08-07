<?php

use League\Route\RouteGroup;

/**
 * @var RouteGroup $router
 * The application's main router instance.
 * Responsible for dispatching HTTP requests to the appropriate route handler.
 */
$router->map('GET', '/admin/users', [\App\Controllers\User::class, 'index'])->middleware(new \App\Middlewares\Example());