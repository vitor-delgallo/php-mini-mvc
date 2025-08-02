<?php
namespace System\Core;

use Laminas\Diactoros\Response as ManualResponse;
use Laminas\Diactoros\Response\HtmlResponse;
use Laminas\Diactoros\Response\JsonResponse;
use Laminas\Diactoros\Response\RedirectResponse;
use Laminas\Diactoros\Stream;
use \Psr\Http\Message\ResponseInterface;

/**
 * HTTP response factory for the application.
 *
 * This class provides a set of static methods to generate PSR-7 compliant responses,
 * using the Laminas Diactoros implementation. It centralizes the logic for returning
 * different types of responses such as:
 *
 * - HTTP redirects (with intelligent status code detection)
 * - HTML responses
 * - JSON responses (raw or encoded)
 * - XML responses
 *
 * All methods return immutable PSR-7 `ResponseInterface` objects.
 *
 * Example usage:
 * ```php
 * return Response::redirect('/login');
 * return Response::html('<h1>Welcome</h1>');
 * return Response::json(['success' => true]);
 * return Response::xml('<note><to>User</to></note>');
 * ```
 */
class Response {
    /**
     * Create a redirect response to a given URI.
     * Automatically detects the appropriate status code based on server and request context.
     *
     * @param string      $uri    Target URI to redirect to.
     * @param string      $method Redirect method: 'auto' | 'refresh' | other.
     * @param int|null    $code   HTTP status code for redirect (optional).
     * @return ManualResponse|RedirectResponse
     */
    public static function redirect(string $uri = '', string $method = 'auto', ?int $code = null): ManualResponse|RedirectResponse {
        if (!preg_match('#^(\w+:)?//#i', $uri)) {
            $uri = Path::siteURL($uri);
        }

        if ($method === 'auto' && isset($_SERVER['SERVER_SOFTWARE']) && str_contains($_SERVER['SERVER_SOFTWARE'], 'Microsoft-IIS')) {
            $method = 'refresh';
        } elseif ($method !== 'refresh' && (empty($code) || !is_numeric($code))) {
            if (
                isset($_SERVER['SERVER_PROTOCOL'], $_SERVER['REQUEST_METHOD']) &&
                $_SERVER['SERVER_PROTOCOL'] === 'HTTP/1.1'
            ) {
                $code = ($_SERVER['REQUEST_METHOD'] !== 'GET') ? 303 : 307;
            } else {
                $code = 302;
            }
        }

        if ($method === 'refresh') {
            return (new ManualResponse())
                ->withStatus(200)
                ->withHeader('Refresh', '0;url=' . $uri);
        }

        return new RedirectResponse($uri, $code);
    }

    /**
     * Create a plain HTML response.
     *
     * @param string $html   HTML content to return.
     * @param int    $status HTTP status code (default: 200).
     * @return HtmlResponse
     */
    public static function html(string $html, int $status = 200): HtmlResponse {
        return new HtmlResponse($html, $status);
    }

    /**
     * Create a JSON response.
     *
     * Accepts either an array (automatically encoded) or a raw JSON string.
     *
     * @param array|string $data   Data to send as JSON.
     * @param int          $status HTTP status code (default: 200).
     * @return JsonResponse
     */
    public static function json(array|string $data, int $status = 200): JsonResponse {
        if (is_string($data)) {
            // Treat as raw JSON (do not re-encode)
            $body = new Stream('php://memory', 'rw');
            $body->write($data);

            return (new JsonResponse(null, $status)) // prevent auto-encoding
                ->withBody($body)
                ->withHeader('Content-Type', 'application/json');
        }

        // Array → json_encode automatically
        return new JsonResponse($data, $status);
    }

    /**
     * Create an XML response with proper headers.
     *
     * @param string $xml    XML content to return.
     * @param int    $status HTTP status code (default: 200).
     * @return ManualResponse
     */
    public static function xml(string $xml, int $status = 200): ManualResponse {
        $body = new Stream('php://memory', 'rw');
        $body->write($xml);

        return (new ManualResponse())
            ->withBody($body)
            ->withStatus($status)
            ->withHeader('Content-Type', 'application/xml; charset=utf-8');
    }
}
