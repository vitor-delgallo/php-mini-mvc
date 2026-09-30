<?php

use MiladRahimi\PhpRouter\Router;

/**
 * @var Router $router
 * The framework/system web router instance.
 *
 * Open in development; anywhere else SystemWebAuth requires SYSTEM_TOKEN (header, bearer or the
 * system_token query parameter) and answers 404 otherwise.
 */
$router->group(['middleware' => [\System\Middlewares\SystemWebAuth::class]], function (Router $router) {
    $router->get('/', [\System\Controllers\Home::class, 'index']);
    $router->post('/maintenance/clean-app', [\System\Controllers\Maintenance::class, 'cleanApp']);
});
