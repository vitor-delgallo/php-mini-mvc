<?php

use MiladRahimi\PhpRouter\Router;

/**
 * @var MiladRahimi\PhpRouter\Router $router
 * The application's main router instance.
 * Responsible for dispatching HTTP requests to the appropriate route handler.
 */
$router->get('/', function () {
    $html = view_render_page('home');
    return response_html($html);
});
$router->group(['middleware' => [\App\Middlewares\Example::class], 'prefix' => '/admin'], function(Router $router) {
    $router->get('/users/{id}', [\App\Controllers\User::class, 'showPage']);
    $router->get('/go-to-users', [\App\Controllers\User::class, 'redirectToList']);
});