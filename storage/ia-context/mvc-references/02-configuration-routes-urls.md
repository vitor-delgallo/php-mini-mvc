# Configuration, Routes, and URLs

## `.env` Variables

```dotenv
APP_ENV=development
BASE_PATH=/php-mini-mvc
DEFAULT_LANGUAGE=en
APP_HELPERS_AUTOLOAD=true

SESSION_DRIVER=none
SESSION_PREFIX=
SESSION_ENCRYPT_KEY=

DB_DRIVER=none
DB_HOST=
DB_PORT=
DB_NAME=
DB_USER=
DB_PASS=
DB_CHARSET=utf8
```

Important rules:

- `APP_ENV` accepts `production`, `development`, or `testing`.
- Environment fallback should be treated as `production`.
- `BASE_PATH` should be used when the app runs from a subdirectory.
- `DEFAULT_LANGUAGE` defines the default language for translations.
- `APP_HELPERS_AUTOLOAD` can load all app helpers or a specific list.
- `SESSION_DRIVER` accepts `files`, `db`, or `none`.
- `DB_DRIVER` accepts `mysql`, `pgsql`, or `none`.

## Routes

Routes use `miladrahimi/phprouter`.

Each route file receives a local variable:

```php
$router
```

## Web Routes

File:

```text
app/routes/web.php
```

Example:

```php
$router->get('/', function () {
    $html = view_render_page('home');
    return response_html($html);
});

$router->group([
    'middleware' => [\App\Middlewares\Example::class],
    'prefix' => '/admin',
], function ($router) {
    $router->get('/users/{id}', [\App\Controllers\User::class, 'showPage']);
});
```

## API Routes

File:

```text
app/routes/api.php
```

This file is loaded with the global `/api` prefix.

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
