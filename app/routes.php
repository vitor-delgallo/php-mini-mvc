<?php

/**
 * @var League\Route\Router $router
 * The application's main router instance.
 * Responsible for dispatching HTTP requests to the appropriate route handler.
 */
$router->map('GET', '/', static function () {
    $html = view_render_page('home');
    return response_html($html);
});
$router->map('GET', '/users', [\App\Controllers\User::class, 'index']);
$router->map('GET', '/users/{id}', [\App\Controllers\User::class, 'showPage']);
$router->map('GET', '/go-to-users', [\App\Controllers\User::class, 'redirectToList']);