# System\Core\Response

Source: `system/Core/Response.php`  
Helper source: `system/helpers/response.php`  
Namespace: `System\Core`

Builds PSR-7 responses using Laminas Diactoros.

## Static Usage

```php
use System\Core\Response;

return Response::json(['ok' => true]);
```

## Helper Usage

```php
return response_json(['ok' => true]);
```

## Method And Helper Signatures

| Static method | Helper | Accepts | Returns |
| --- | --- | --- | --- |
| `Response::redirect(string $uri = '', string $method = 'auto', ?int $code = null): ManualResponse|RedirectResponse` | `response_redirect(string $uri = '', string $method = 'auto', ?int $code = null): ResponseInterface` | Target URI, redirect method, and optional status code. Relative URIs are converted through `Path::siteURL()`. | Redirect response or refresh-header response. |
| `Response::html(string $html, int $status = 200): HtmlResponse` | `response_html(string $html, int $status = 200): ResponseInterface` | HTML string and HTTP status. | HTML response. |
| `Response::text(string $text, int $status = 200): TextResponse` | `response_text(string $text, int $status = 200): ResponseInterface` | Plain text and HTTP status. | Text response. |
| `Response::json(array|string $data, int $status = 200): JsonResponse` | `response_json(array|string $data, int $status = 200): ResponseInterface` | Array to encode or raw JSON string, plus HTTP status. | JSON response with `application/json`. |
| `Response::xml(string $xml, int $status = 200): ManualResponse` | `response_xml(string $xml, int $status = 200): ResponseInterface` | XML string and HTTP status. | Response with `application/xml; charset=utf-8`. |
| `Response::file(string $filePath, string $downloadName, string $hashFile, string $contentType = 'application/octet-stream'): ResponseInterface` | `response_file(string $filePath, string $downloadName, string $hashFile, string $contentType = 'application/octet-stream'): ResponseInterface` | Readable file path, suggested download name, hash value for headers, and MIME type. | Download response, 404 text response, or 500 text response. |

## Redirect Method Rules

- `$method = 'auto'` selects normal redirects except for Microsoft IIS, where it switches to refresh mode.
- `$method = 'refresh'` returns a `Refresh` header response with status 200.
- When `$code` is omitted, POST-like HTTP/1.1 requests use 303, GET requests use 307, and fallback behavior uses 302.

## Notes

- Controllers should return `Psr\Http\Message\ResponseInterface`; helpers are the shortest way to do that.
- For `response_json()`, strings are treated as already-encoded JSON and are not encoded again.
