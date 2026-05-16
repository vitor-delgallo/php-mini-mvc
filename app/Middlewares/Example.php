<?php
namespace App\Middlewares;

use Psr\Http\Message\ServerRequestInterface;
use System\Core\Response;

class Example {
    public function handle(ServerRequestInterface $request, \Closure $next) {
        if (1 === 0) {
            return Response::redirect('/');
        }

        return $next($request);
    }
}
