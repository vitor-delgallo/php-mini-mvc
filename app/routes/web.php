<?php

use League\Route\RouteGroup;

/**
 * @var League\Route\Router $router
 * The application's main router instance.
 * Responsible for dispatching HTTP requests to the appropriate route handler.
 */
$router->map('GET', '/', static function () {
    $html = view_render_page('home');
    return response_html($html);
});
$router->group('/admin', function (RouteGroup $group) {
    $group->map('GET', '/users/{id}', [\App\Controllers\User::class, 'showPage']);
    $group->map('GET', '/go-to-users', [\App\Controllers\User::class, 'redirectToList']);
})->middleware(new \App\Middlewares\Example());