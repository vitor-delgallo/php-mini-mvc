<?php

use MiladRahimi\PhpRouter\Router;

/**
 * @var Router $router
 * The framework/system API router instance.
 */
$systemApiHome = function () {
    return response_json([
        'source' => 'system',
        'type' => 'api',
    ]);
};

$router->get('', $systemApiHome);
$router->get('/', $systemApiHome);

$systemI18n = [\System\Controllers\I18n::class, 'index'];

$router->get('/i18n', $systemI18n);
$router->get('/i18n/{prefix}', $systemI18n);
