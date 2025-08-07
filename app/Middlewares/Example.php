<?php
namespace App\Middlewares;

use Psr\Http\Message\ServerRequestInterface;

class Example {
    public function handle(ServerRequestInterface $request, \Closure $next) {
        if (1 === 0) {
            return response_redirect('/');
        }

        return $next($request);
    }
}