<?php

/**
 * @var MiladRahimi\PhpRouter\Router $router
 * The framework/system web router instance.
 */
$systemHome = [\System\Controllers\Home::class, 'index'];

$router->get('', $systemHome);
$router->get('/', $systemHome);
