[Read in Brazilian Portuguese](README.pt-br.md)

# PHP Mini MVC

PHP Mini MVC is a small, direct MVC framework for PHP 8.2+. It is designed for landing pages, institutional websites, small systems, simple APIs, and traditional HTML/CSS/JavaScript applications.

The project favors predictable structure, procedural helper functions, PSR-7 responses, JSON-based translations, PDO database access, and a minimal dependency set.

## What This Project Is

- A lightweight MVC project skeleton for PHP 8.2+.
- A simple framework core under `system/`.
- A conventional application layer under `app/`.
- A good fit for small web projects and simple APIs.
- A codebase intended to stay easy to read, modify, and deploy.

## What This Project Is Not

- It is not Laravel, Symfony, Slim, or a full-stack enterprise framework.
- It does not assume React, Vue, Angular, jQuery, or a front-end build pipeline.
- It does not try to hide PHP behind heavy abstractions.

## Requirements

- PHP 8.2 or newer
- Composer
- PHP extensions:
  - `openssl`
  - `pdo`
- A web server that can route requests to `public/index.php`

## Main Dependencies

- `miladrahimi/phprouter` for routing
- `laminas/laminas-diactoros` for PSR-7 responses
- `laminas/laminas-httphandlerrunner`
- `vlucas/phpdotenv` for environment variables
- PDO for database access

## Installation

```bash
git clone https://github.com/vitor-delgallo/php-mini-mvc.git
cd php-mini-mvc
composer install
```

Create your environment file:

```bash
cp .env.example .env
```

On Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Point your web server to `public/`, or use the included Apache `.htaccess` from the project root to route requests to `public/index.php`.

## Environment

The main `.env` values are:

```dotenv
APP_ENV=development
BASE_PATH=/php-mini-mvc
DEFAULT_LANGUAGE=en
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

Important values:

- `APP_ENV`: `production`, `development`, or `testing`.
- `BASE_PATH`: use this when the app runs from a subdirectory, such as `/php-mini-mvc`.
- `DEFAULT_LANGUAGE`: default language used by the translation system.
- `APP_HELPERS_AUTOLOAD`: `true` to load all app helpers, or a list such as `['auth','format.php']`.
- `SESSION_DRIVER`: `files`, `db`, or `none`.
- `SESSION_DB`: optional named connection for `SESSION_DRIVER=db`; blank uses the default `DB_*` connection.
- `DB_DRIVER`: `mysql`, `pgsql`, or `none` for the default database connection.
- `DB_*_<SUFFIX>`: optional named database connections, such as `DB_DRIVER_APP`, `DB_HOST_AUTH`, or `DB_NAME_ROBOT`.

## Project Structure

```text
app/
  Bootable/         Classes executed during bootstrap when they implement IBootable
  Controllers/      Application controllers, namespace App\Controllers
  Middlewares/      Route and route-group middlewares
  Models/           Application models, namespace App\Models
  helpers/          App-specific helpers
  routes/           web.php and api.php route files
  views/
    pages/          Page views
    templates/      Reusable templates/layouts

languages/          Language JSON files
public/             Expected document root
storage/
  ia-context/       Context files for AI agents and maintainers
  logs/             Daily logs
  sessions/         Session files when SESSION_DRIVER=files

system/
  Config/           Configuration and environment resolvers
  Core/             Framework core classes
  helpers/          Internal procedural helpers
  includes/         Error and session handlers
  Interfaces/       System contracts
  Session/          Custom session handlers
```

## Request Lifecycle

All requests enter through:

```text
public/index.php
```

The bootstrap:

1. Loads Composer autoload.
2. Loads error handlers.
3. Loads `.env`.
4. Detects whether the request is an API request.
5. Loads system helpers and app helpers.
6. Configures sessions for web requests, or disables cookies and uses `NULLHandler` for API requests.
7. Connects to the default database when `DB_DRIVER` is not `none`; named database connections are opened lazily.
8. Executes bootable classes in `app/Bootable`.
9. Loads web routes from `app/routes/web.php` or API routes from `app/routes/api.php` with the `/api` prefix.
10. Dispatches the request through the router.

## Routing

Routes use `miladrahimi/phprouter`. Each route file receives a local `$router` variable.

Web routes live in:

```text
app/routes/web.php
```

Example:

```php
use MiladRahimi\PhpRouter\Router;

$router->get('/', function () {
    return response_html(view_render_page('home'));
});

$router->group([
    'middleware' => [\App\Middlewares\Example::class],
    'prefix' => '/admin',
], function (Router $router) {
    $router->get('/users/{id}', [\App\Controllers\User::class, 'showPage']);
});
```

API routes live in:

```text
app/routes/api.php
```

They are loaded with the global `/api` prefix:

```php
$router->group(['prefix' => '/admin'], function (Router $router) {
    $router->get('/users', [\App\Controllers\User::class, 'index']);
});
```

With `BASE_PATH=/php-mini-mvc`, the API route above is available at:

```text
/php-mini-mvc/api/admin/users
```

## Controllers

Controllers live in `app/Controllers` and use the `App\Controllers` namespace. They should return `Psr\Http\Message\ResponseInterface`.

```php
namespace App\Controllers;

use App\Models\User as UserModel;
use Psr\Http\Message\ResponseInterface;

class User
{
    public function index(): ResponseInterface
    {
        return response_json(UserModel::all());
    }

    public function showPage(int $id): ResponseInterface
    {
        $user = UserModel::find($id);

        if (empty($user)) {
            return response_html(view_render_html('<h4>User not found</h4>'), 404);
        }

        return response_html(view_render_page('user-profile', [
            'user' => $user,
        ]));
    }
}
```

Recommended pattern:

- Keep HTTP response decisions in controllers.
- Keep queries and persistence in models.
- Keep HTML in views.
- Return responses through `response_html()`, `response_json()`, `response_redirect()`, and related helpers.

## Views and Templates

Page views live in:

```text
app/views/pages/
```

Templates live in:

```text
app/views/templates/
```

Render a page:

```php
return response_html(view_render_page('home', [
    'title' => 'Home',
]));
```

Render a small raw HTML block:

```php
return response_html(view_render_html('<h1>OK</h1>'));
```

Share data globally with views:

```php
view_share('appName', 'PHP Mini MVC');
view_share_many(['theme' => 'light']);
```

## Assets and URLs

Use path helpers instead of hard-coded absolute paths.

```php
<link rel="stylesheet" href="<?= path_base_public() ?>/assets/css/app.css">
<script src="<?= path_base_public() ?>/assets/js/app.js"></script>
```

Use `site_url()` for absolute URLs:

```php
$loginUrl = site_url('/login');
```

This keeps the project compatible with subdirectory deployments through `BASE_PATH`.

## Language System

Translations live in `languages/` as JSON files. Files in subfolders receive prefixes based on the folder path.

Example:

```text
languages/system/en.json      -> system.http.404.title
languages/template/en.json    -> template.framework.name
languages/pages/users/en.json -> pages.users.profile
```

Usage:

```php
echo lg('template.framework.name');

echo lg('system.database.connection.error.info', [
    'error' => $message,
]);
```

Loading priority:

1. Full requested or detected language, such as `pt-br`.
2. Short language prefix, such as `pt`.
3. `DEFAULT_LANGUAGE`.
4. Empty translations when nothing is found.

## Database

Set `DB_DRIVER=mysql` or `DB_DRIVER=pgsql` in `.env` and configure the remaining default database variables. The bootstrap connects automatically to the default connection when the driver is not `none`.

You can add named connections with uppercase suffixes. The lowercase suffix becomes the connection name:

```dotenv
DB_DRIVER_APP=mysql
DB_HOST_APP=localhost
DB_NAME_APP=app_db
DB_USER_APP=app_user
DB_PASS_APP=
DB_PORT_APP=
DB_CHARSET_APP=utf8mb4

DB_DRIVER_AUTH=pgsql
DB_HOST_AUTH=localhost
DB_NAME_AUTH=auth_db
DB_USER_AUTH=auth_user
```

For each suffixed connection, `DRIVER`, `HOST`, `NAME`, and `USER` are required. `PASS`, `PORT`, and `CHARSET` are optional. Missing or incomplete suffixed groups are ignored. Ports default to `3306` for MySQL and `5432` for PostgreSQL; charset defaults to `utf8`.

Use the database helpers:

```php
$users = database_select(
    'SELECT id, name FROM users WHERE active = ?',
    [1]
);

$user = database_select_row(
    'SELECT * FROM users WHERE id = :id',
    ['id' => $id]
);

database_statement(
    'INSERT INTO users (name, email) VALUES (:name, :email)',
    ['name' => $name, 'email' => $email]
);

$authUsers = database_select(
    'SELECT id, email FROM users WHERE active = ?',
    [1],
    null,
    'auth'
);
```

Connections can also be registered at runtime when they should not come from the environment:

```php
database_configure('reporting', [
    'driver' => 'mysql',
    'host' => '127.0.0.1',
    'name' => 'reports',
    'user' => 'report_user',
]);

$reports = database_select('SELECT id, title FROM reports', [], null, 'reporting');

database_forget_connection('reporting');
```

Transactions:

```php
database_start_transaction();

try {
    database_statement('UPDATE accounts SET balance = balance - ? WHERE id = ?', [100, $from]);
    database_statement('UPDATE accounts SET balance = balance + ? WHERE id = ?', [100, $to]);

    database_commit_transaction();
} catch (Throwable $e) {
    if (database_is_in_transaction()) {
        database_rollback_transaction();
    }

    throw $e;
}
```

Always use parameters or prepared statements. Do not concatenate user input directly into SQL.

## Sessions

Configure sessions with:

```dotenv
SESSION_DRIVER=none
SESSION_DB=
```

Supported drivers:

- `files`: native file-backed sessions in `storage/sessions`.
- `db`: database-backed sessions through `System\Session\DBHandler`.
- `none`: session handling disabled.

When `SESSION_DRIVER=db`, sessions use the default `DB_*` connection unless `SESSION_DB` names another configured connection, such as `app`, `auth`, or `robot`. If the selected connection is missing, incomplete, unsupported, or disabled, the framework treats it as an internal configuration error.

Common helpers:

```php
session_set('user_id', 123);

if (session_has('user_id')) {
    $id = session_get('user_id');
}

session_regenerate();
session_save();
```

API routes should not use sessions. API requests use `System\Session\NULLHandler`.

## Form Validation

Use `form_validator()` for request data:

```php
$form = form_validator($_POST, reset: true);

if (!$form->validate([
    'email' => 'required|email',
    'password' => 'required|min:8',
])) {
    return response_html(view_render_page('form', [
        'errors' => $form->errors(),
    ]), 422);
}
```

Native rules include:

```text
required
email
min:{n}
max:{n}
same:{field}
numeric
integer
date
regex:{pattern}
in:{a,b,c}
```

For arrays, use `..` to iterate child items:

```php
$form->validate([
    'users..email' => 'required|email',
]);
```

## Bootables

Bootable classes live in `app/Bootable` and implement `System\Interfaces\IBootable`.

```php
namespace App\Bootable;

use System\Interfaces\IBootable;
use System\Core\View;

class ShareDefaults implements IBootable
{
    public static function boot(): void
    {
        View::share('appName', 'PHP Mini MVC');
    }
}
```

Bootables run on every request. Keep them lightweight and avoid global queries or expensive initialization.

## AI Context

This repository includes structured context for AI coding agents:

```text
storage/ia-context/mvc.md
storage/ia-context/mvc-references/
```

Use `storage/ia-context/mvc.md` as the entry point. The reference files split architecture, configuration, MVC layers, languages, database/session/forms, responses/middlewares/bootables, helpers, workflows, and cautions into focused documents.

## Development Principles

- Keep the framework small and predictable.
- Prefer existing helpers and core classes before adding new abstractions.
- Avoid heavy dependencies unless they are explicitly justified.
- Preserve `BASE_PATH` support.
- Keep API routes stateless.
- Keep views simple.
- Keep models responsible for data access.
- Keep SQL parameterized.

## Contributing

Contributions are welcome. You can open issues for bugs, proposals, or documentation improvements, and pull requests for focused changes.

Before changing the framework core, prefer small, testable updates that preserve the existing project structure.

Repository: [vitor-delgallo/php-mini-mvc](https://github.com/vitor-delgallo/php-mini-mvc)

## License

This project is released under the [MIT License](LICENSE).
