<?php
namespace System\Controllers;

use Psr\Http\Message\ResponseInterface;
use System\Config\Globals;
use System\Core\Path;
use System\Core\Response;
use System\Core\View;

class Home {
    public function index(): ResponseInterface {
        return Response::html(View::render_system_page('home', [
            'maintenanceCleanup' => [
                'endpoint' => Path::basePath() . Globals::getSystemWebPrefix() . '/maintenance/clean-app',
                'nonce' => Maintenance::createCleanupNonce(),
            ],
        ]))
            ->withHeader('X-Robots-Tag', 'noindex, nofollow, noarchive, nosnippet, noimageindex');
    }
}
