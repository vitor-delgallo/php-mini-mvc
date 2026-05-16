<?php
namespace System\Middlewares;

use Closure;
use Psr\Http\Message\ServerRequestInterface;
use System\Config\Globals;
use System\Core\Response;

class SystemI18nAuth {
    public function handle(ServerRequestInterface $request, Closure $next) {
        $systemToken = $this->systemToken();
        if ($systemToken === null) {
            return Response::json(['error' => 'system_i18n_disabled'], 404);
        }

        if (!$this->hasValidToken($request, $systemToken)) {
            return Response::json(['error' => 'forbidden'], 403);
        }

        return $next($request);
    }

    private function systemToken(): ?string {
        $token = Globals::env('SYSTEM_TOKEN');
        if (!is_string($token)) {
            return null;
        }

        $token = trim($token);
        return $token === '' ? null : $token;
    }

    private function hasValidToken(ServerRequestInterface $request, string $systemToken): bool {
        $requestToken = $this->requestToken($request);
        if ($requestToken === null) {
            return false;
        }

        return hash_equals($systemToken, $requestToken);
    }

    private function requestToken(ServerRequestInterface $request): ?string {
        $headerToken = $request->getHeaderLine('X-System-Token');
        if (trim($headerToken) !== '') {
            return trim($headerToken);
        }

        $authorization = $request->getHeaderLine('Authorization');
        if (trim($authorization) === '') {
            $authorization = $_SERVER['HTTP_AUTHORIZATION']
                ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION']
                ?? '';
        }

        return preg_match('/^\s*Bearer\s+(.+?)\s*$/i', $authorization, $matches)
            ? $matches[1]
            : null;
    }
}
