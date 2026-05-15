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

Middlewares live in:

```text
app/Middlewares
```

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
