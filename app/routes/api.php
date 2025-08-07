<?php

use MiladRahimi\PhpRouter\Router;

/**
 * @var Router $router
 * The application's main router instance.
 * Responsible for dispatching HTTP requests to the appropriate route handler.
 */
$router->group(['middleware' => [\App\Middlewares\Example::class], 'prefix' => '/admin'], function(Router $router) {
    $router->get('/users', [\App\Controllers\User::class, 'index']);
});