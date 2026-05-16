<?php

use MiladRahimi\PhpRouter\Router;
use System\Core\Response;

/**
 * @var MiladRahimi\PhpRouter\Router $router
 * The application's main router instance.
 * Responsible for dispatching HTTP requests to the appropriate route handler.
 */
$router->get('/', function () {
    return Response::redirect('/web-system');
});
$router->group(['middleware' => [\App\Middlewares\Example::class], 'prefix' => '/admin'], function(Router $router) {
    $router->get('/users/{id}', [\App\Controllers\User::class, 'showPage']);
    $router->get('/go-to-users', [\App\Controllers\User::class, 'redirectToList']);
});
