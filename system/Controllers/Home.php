<?php
namespace System\Controllers;

use Psr\Http\Message\ResponseInterface;
use System\Core\Response;
use System\Core\View;

class Home {
    public function index(): ResponseInterface {
        return Response::html(View::render_system_page('home'));
    }
}
