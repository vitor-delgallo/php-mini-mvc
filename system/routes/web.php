<?php

/**
 * @var MiladRahimi\PhpRouter\Router $router
 * The framework/system web router instance.
 */
$router->get('/', [\System\Controllers\Home::class, 'index']);
$router->post('/maintenance/clean-app', [\System\Controllers\Maintenance::class, 'cleanApp']);
