<?php

/**
 * @var MiladRahimi\PhpRouter\Router $router
 * The framework/system web router instance.
 */
$router->get('/', [\System\Controllers\Home::class, 'index']);
