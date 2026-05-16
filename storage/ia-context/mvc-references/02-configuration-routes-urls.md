# Configuration, Routes, and URLs

## `.env` Variables

```dotenv
APP_ENV=development
BASE_PATH=/php-mini-mvc
DEFAULT_LANGUAGE=en
SYSTEM_TOKEN=
APP_HELPERS_AUTOLOAD=true

SESSION_DRIVER=none
SESSION_DB=
SESSION_PREFIX=
SESSION_ENCRYPT_KEY=

DB_DRIVER=none
DB_HOST=
DB_PORT=
DB_NAME=
DB_USER=
DB_PASS=
DB_CHARSET=utf8

DB_DRIVER_APP=
DB_HOST_APP=
DB_PORT_APP=
DB_NAME_APP=
DB_USER_APP=
DB_PASS_APP=
DB_CHARSET_APP=
```

Important rules:

- `APP_ENV` accepts `production`, `development`, or `testing`.
- Environment fallback should be treated as `production`.
- `BASE_PATH` should be used when the app runs from a subdirectory.
- `DEFAULT_LANGUAGE` defines the default language for translations.
- `SYSTEM_TOKEN` protects system API routes such as `/api-system/i18n`; leave it empty to disable those routes. `System\Middlewares\SystemI18nAuth` enforces this token for i18n routes. Vue pages that fetch translations directly receive this token in browser boot data, so use it only for framework utility endpoints, not private user data.
- `APP_HELPERS_AUTOLOAD` can load all app helpers or a specific list.
- `SESSION_DRIVER` accepts `files`, `db`, or `none`.
- `SESSION_DB` optionally selects a named connection for `SESSION_DRIVER=db`; empty uses the default `DB_*` connection.
- `DB_DRIVER` accepts `mysql`, `pgsql`, or `none` for the default connection.
- `DB_*_<SUFFIX>` creates optional named connections such as `app`, `auth`, or `robot`. `DRIVER`, `HOST`, `NAME`, and `USER` are required per suffix; `PASS`, `PORT`, and `CHARSET` are optional.
- Incomplete suffixed database groups are ignored. Complete suffixed groups with unsupported drivers are configuration errors.

## Routes

Routes use `miladrahimi/phprouter`.

Each route file receives a local variable:

```php
$router
```

Declare root handlers with `/`:

```php
$router->get('/', $handler);
```

When a route file is loaded under a prefix, `RouterLoader` lets that root handler match both the exact prefix URL and the same URL with a trailing slash. For example, a `/` route under `/web-system` matches both `/web-system` and `/web-system/`.

This normalization is limited to exact root-prefix routes. Non-root routes keep exact trailing-slash behavior.

## App Web Routes

File:

```text
app/routes/web.php
```

The app root redirects to the framework documentation home:

```php
$router->get('/', function () {
    return response_redirect('/web-system');
});
```

Example:

```php
$router->group([
    'middleware' => [\App\Middlewares\Example::class],
    'prefix' => '/admin',
], function ($router) {
    $router->get('/users/{id}', [\App\Controllers\User::class, 'showPage']);
});
```

## App API Routes

File:

```text
app/routes/api.php
```

This file is loaded with the app API `/api` prefix.

Example:

```php
$router->group(['prefix' => '/admin'], function ($router) {
    $router->get('/users', [\App\Controllers\User::class, 'index']);
});
```

If `BASE_PATH=/php-mini-mvc`, a `/api/health` route will be available at:

```text
/php-mini-mvc/api/health
```

APIs should return JSON, text, XML, files, or another PSR-7 response. Do not use sessions in API routes.

## System Web Routes

File:

```text
system/routes/web.php
```

This file is loaded with the system web `/web-system` prefix. Framework-owned web pages live here. The documentation home is served at:

```text
/web-system
```

Declare the route as `/`; the loader also accepts `/web-system/`.

With `BASE_PATH=/php-mini-mvc`, the final URL is:

```text
/php-mini-mvc/web-system
```

## System API Routes

File:

```text
system/routes/api.php
```

This file is loaded with the system API `/api-system` prefix. System API requests are treated as API requests and do not use sessions.

With `BASE_PATH=/php-mini-mvc`, a system API route at `/health` would be available at:

```text
/php-mini-mvc/api-system/health
```

A system API home route should be declared as `/`; the loader also accepts `/api-system` without requiring a duplicate `''` route.

The protected system i18n endpoint exposes selected translations for system consumers:

```text
GET /api-system/i18n?prefix=app.pages&lang=en
X-System-Token: <SYSTEM_TOKEN>
```

`Authorization: Bearer <SYSTEM_TOKEN>` is also accepted. If `SYSTEM_TOKEN` is empty or undefined, the endpoint is disabled and returns 404. Invalid or missing tokens return 403.

Authentication is owned by `System\Middlewares\SystemI18nAuth`, which is applied only to the i18n routes in `system/routes/api.php`. `System\Controllers\I18n` should only parse `prefix` / `lang`, load translations, and return the response shape.

Successful response shape:

```json
{
  "lang": "en",
  "prefix": "app.pages.",
  "translations": {
    "app.pages.users.profile": "User Profile"
  }
}
```

## `BASE_PATH` and URLs

When the project runs from a subdirectory, configure `BASE_PATH` in `.env`.

To generate compatible URLs and paths, prefer:

```php
path_base();
path_base_public();
site_url();
```

In views, for assets:

```php
<link rel="stylesheet" href="<?= path_base_public() ?>/assets/css/app.css">
<script src="<?= path_base_public() ?>/assets/js/app.js"></script>
```

Do not write fixed absolute paths such as `/assets/...` when the project may run from a subdirectory.

`BASE_PATH` also applies to route prefixes. For example, `/api`, `/web-system`, and `/api-system` become `/php-mini-mvc/api`, `/php-mini-mvc/web-system`, and `/php-mini-mvc/api-system` when `BASE_PATH=/php-mini-mvc`.

## Main Path Helpers

Main class:

```php
System\Core\Path
```

Common helpers:

```php
path_root();
path_app();
path_app_bootable();
path_app_helpers();
path_app_languages();
path_app_routes();
path_app_middlewares();
path_app_controllers();
path_app_models();
path_app_views();
path_app_views_pages();
path_app_views_templates();
path_system();
path_system_interfaces();
path_system_helpers();
path_system_languages();
path_system_routes();
path_system_middlewares();
path_system_controllers();
path_system_models();
path_system_views();
path_system_views_pages();
path_system_views_templates();
path_system_includes();
path_public();
path_storage();
path_storage_sessions();
path_storage_logs();
path_languages();
path_base();
path_base_public();
site_url();
```

Use `path_base_public()` for public assets and `site_url()` for absolute URLs.

## Route Loader Helpers

Main class:

```php
System\Core\RouterLoader
```

Helpers:

```php
router_loader_load('web');
router_loader_load_with_prefix('/api', 'api');
router_loader_load_system('web');
router_loader_load_system_with_prefix('/web-system', 'web');
router_loader_dispatch();
```

Use app route helpers for files in `app/routes` and system route helpers for files in `system/routes`. Prefixes are normalized by the loader and must still preserve `BASE_PATH` compatibility.

For prefixed route files, declare root routes with `/`. `RouterLoader` tracks the loaded prefix and lets the exact prefix URL without a trailing slash dispatch to that root route when no explicit `''` route exists.
