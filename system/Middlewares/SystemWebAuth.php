<?php
namespace System\Middlewares;

use Closure;
use Psr\Http\Message\ServerRequestInterface;
use System\Config\Environment;
use System\Config\Globals;
use System\Core\Language;
use System\Core\Response;

/**
 * Guards the framework's web area (/web-system): the documentation home and the cleanup endpoint.
 *
 * In development the area is open. Anywhere else it answers 404 unless SYSTEM_TOKEN is configured
 * and the request presents it through `X-System-Token`, `Authorization: Bearer <token>` or, for a
 * plain browser visit, the `system_token` query parameter.
 */
class SystemWebAuth {
    public function handle(ServerRequestInterface $request, Closure $next) {
        if (Environment::isDevelopment()) {
            return $next($request);
        }

        $systemToken = self::configuredToken();
        $provided = self::providedToken();

        if ($systemToken === null || $provided === null || !hash_equals($systemToken, $provided)) {
            return Response::html('<h1>' . Language::get('system.http.404.title') . '</h1>', 404);
        }

        return $next($request);
    }

    /** SYSTEM_TOKEN from the environment, or null when empty. */
    public static function configuredToken(): ?string {
        $token = Globals::env('SYSTEM_TOKEN');
        if (!is_string($token)) {
            return null;
        }

        $token = trim($token);

        return $token === '' ? null : $token;
    }

    /**
     * Token sent with the current request: `X-System-Token`, `Authorization: Bearer`, or the
     * `system_token` query parameter when $allowQuery is true.
     */
    public static function providedToken(bool $allowQuery = true): ?string {
        $header = trim((string) ($_SERVER['HTTP_X_SYSTEM_TOKEN'] ?? ''));
        if ($header !== '') {
            return $header;
        }

        $authorization = (string) ($_SERVER['HTTP_AUTHORIZATION'] ?? $_SERVER['REDIRECT_HTTP_AUTHORIZATION'] ?? '');
        if (preg_match('/^\s*Bearer\s+(.+?)\s*$/i', $authorization, $matches)) {
            return $matches[1];
        }

        if ($allowQuery) {
            $query = $_GET['system_token'] ?? null;
            if (is_string($query) && trim($query) !== '') {
                return trim($query);
            }
        }

        return null;
    }
}
