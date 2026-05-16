# Architecture and Bootstrap

## Purpose

**PHP Mini MVC** is a PHP 8.2+ mini-framework created for lean applications:

- landing pages;
- institutional websites;
- small systems;
- simple APIs;
- traditional front-end projects with HTML, CSS, and JavaScript.

The goal is to keep the structure simple, predictable, low-dependency, and easy to adapt. Before adding new libraries, always check whether an existing helper, core class, or internal pattern already solves the problem.

## Stack

- **PHP 8.2+**
- **Composer**
- **PSR-4** for `App\` and `System\`
- **miladrahimi/phprouter** for routes
- **Laminas Diactoros** for PSR-7 responses
- **vlucas/phpdotenv** for `.env`
- **PDO** for database access

Do not convert the project to Laravel, Symfony, Slim, React, Vue, Angular, or any other larger framework without an explicit request.

## Main Structure

```text
app/
  Bootable/         Classes executed during bootstrap when they implement IBootable
  Controllers/      Application controllers, namespace App\Controllers
  Middlewares/      Route and route-group middlewares
  Models/           Application models, namespace App\Models
  helpers/          App-specific helpers
  languages/        Application-owned translation JSON files, exposed as app.*
  routes/           web.php and api.php route files
  views/
    pages/          Page views
    templates/      Reusable templates/layouts

public/             Expected server document root
storage/
  ia-context/       General AI context documents
  logs/             Daily logs
  sessions/         Session files when SESSION_DRIVER=files

system/
  Config/           Configuration/env resolvers
  Controllers/      Framework/system controllers, namespace System\Controllers
  Core/             Framework core
  helpers/          Internal procedural helpers
  includes/         Error and session handlers
  Interfaces/       System contracts
  languages/        Framework, docs, template, validation, and core error translations, exposed as system.*
  Middlewares/      Framework/system route middlewares, namespace System\Middlewares
  routes/           Framework/system web.php and api.php route files
  Session/          Custom session handlers
  views/            Framework/system views and templates
```

## General Conventions

- Respect the project's own MVC structure.
- Preserve the `app/`, `system/`, `public/`, and `storage/` structure, including split language roots under `app/languages/` and `system/languages/`.
- Use `App\...` for application code.
- Use `System\...` for framework code.
- Controllers must return `Psr\Http\Message\ResponseInterface`.
- Views should be simple and focused on presentation.
- Models should concentrate data access.
- Procedural helpers are the recommended short API for views, controllers, and middlewares.
- Avoid large dependencies for small problems.
- Do not use jQuery, React, Vue, or Angular without an explicit need.
- Prefer Bootstrap 5 and vanilla JavaScript for traditional front-end work.
- Use prepared statements whenever SQL is involved.

## Request Lifecycle

Application entry point:

```text
public/index.php
```

The server should point the document root to `public/`, or redirect all requests to that file.

General flow:

1. Load `../vendor/autoload.php`.
2. Include `system/includes/error_handlers.php`.
3. Run `Globals::loadEnv()`.
4. Read variables from `.env`.
5. Detect whether the request is an API request with `Globals::isApiRequest()`.
6. Configure error display according to `APP_ENV`.
7. Load internal helpers from `system/helpers` through `PHPAutoload::from()` / `php_autoload_from()`.
8. Load app helpers according to `APP_HELPERS_AUTOLOAD`.
9. Configure sessions:
   - web: use `system/includes/session_handlers.php`;
   - API: disable cookies and use `System\Session\NULLHandler`.
10. Automatically connect to the database when `DB_DRIVER` is valid.
11. Execute bootable classes in `app/Bootable` through `PHPAutoload::boot()` / `php_autoload_boot()`.
12. Load routes:
   - web: `app/routes/web.php`;
   - API: `app/routes/api.php` with the `/api` prefix.
13. Dispatch the route through `RouterLoader` / `router_loader_dispatch()`.
14. On `RouteNotFoundException`, return HTML 404.
15. On other errors, return HTML 500; outside production, show details and write the daily log.

## Recommended Order for Agents

1. Read `../mvc.md`.
2. Open only the reference documents related to the task.
3. Identify which files actually need to change.
4. Look for an existing helper, core class, or internal pattern before creating new code.
5. Preserve the project's own MVC style and `BASE_PATH` compatibility.
6. Test the affected behavior.
