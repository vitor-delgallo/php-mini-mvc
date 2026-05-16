# Responses, Middlewares, and Bootables

## HTTP Responses

Main class:

```php
System\Core\Response
```

Helpers:

```php
response_redirect($uri = '', $method = 'auto', $code = null);
response_html($html, $status = 200);
response_text($text, $status = 200);
response_json($data, $status = 200);
response_xml($xml, $status = 200);
response_file($filePath, $downloadName, $hashFile, $contentType = 'application/octet-stream');
```

Examples:

```php
return response_html(view_render_page('user-profile'));

return response_json([
    'status' => 'ok',
]);

return response_redirect('/login');
```

`response_redirect('/login')` turns relative paths into absolute URLs using `Path::siteURL()`.

## Middlewares

Application middlewares live in:

```text
app/Middlewares
```

System-owned middlewares live in:

```text
system/Middlewares
```

Use `App\Middlewares\...` for application behavior and `System\Middlewares\...` for framework/system route behavior.

Example:

```php
namespace App\Middlewares;

use Psr\Http\Message\ServerRequestInterface;

class Auth
{
    public function handle(ServerRequestInterface $request, \Closure $next)
    {
        if (!session_has('user_id')) {
            return response_redirect('/login');
        }

        return $next($request);
    }
}
```

Recommendations:

- keep middlewares small;
- avoid heavy global middlewares;
- use middlewares per route or route group;
- do not use sessions in API routes.
- do not create procedural helpers for middleware `handle()` methods; they are invoked by the router.

## System I18n Auth Middleware

The protected translation endpoint `/api-system/i18n` uses:

```php
System\Middlewares\SystemI18nAuth
```

This middleware owns `SYSTEM_TOKEN` validation for i18n routes:

- empty or missing `SYSTEM_TOKEN` returns JSON 404 with `system_i18n_disabled`;
- missing or invalid request token returns JSON 403 with `forbidden`;
- `X-System-Token: <token>` is accepted;
- `Authorization: Bearer <token>` is also accepted;
- token comparison uses `hash_equals`.

`System\Controllers\I18n` should not validate tokens directly. It should only handle `prefix`, `lang`, translation loading, and response data.

System controller actions and middleware handlers are not helper-backed APIs. Route files should reference their classes directly.

## Bootables

Bootable classes live in:

```text
app/Bootable
```

They must implement:

```php
System\Interfaces\IBootable
```

Contract:

```php
namespace System\Interfaces;

interface IBootable
{
    public static function boot(): void;
}
```

Example:

```php
namespace App\Bootable;

use System\Interfaces\IBootable;
use System\Core\View;

class ShareDefaults implements IBootable
{
    public static function boot(): void
    {
        View::share('appName', 'My App');
    }
}
```

Rules:

- bootables run on every request;
- keep bootables lightweight;
- avoid global queries;
- avoid heavy logic;
- use them for small configuration, shared variables, and simple initialization.

Bootstrap executes bootables through `System\Core\PHPAutoload::boot()`, also exposed as `php_autoload_boot()`. Use `php_autoload_from()` only for controlled bootstrap-style directory loading.

Do not create a procedural helper for `IBootable::boot()` implementations. The contract is executed through the autoload boot pipeline.
