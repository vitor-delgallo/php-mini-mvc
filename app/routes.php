<?php

use System\Core\Path;

include Path::systemIncludes() . '/router_init.php';

/** @var League\Route\Router $router */
$router->map('GET', '/', static function () {
    $html = view_render('home');
    return response_html($html);
});

$router->map('GET', '/hello', static function () {
    return response_html('oi2');
});

include Path::systemIncludes() . '/router_dispatch.php';