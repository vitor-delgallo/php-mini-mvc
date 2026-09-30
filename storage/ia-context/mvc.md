---
name: php-mini-mvc-context
description: Essential context for AI agents working on PHP Mini MVC without rediscovering the internal architecture. Use when changing routes, controllers, models, views, helpers, database code, session behavior, translations, assets, or bootstrap logic in this PHP 8.2 mini-framework.
---

# PHP Mini MVC Context

This file is the entry point for **PHP Mini MVC** context for AI agents, coding agents, and developers.

Keep this document short enough to remain in context by default. Open the documents in `mvc-references/` only when the task requires details about that topic.

## Essential Context Agents Should Always Know

- The project is a **PHP 8.2+** MVC mini-framework for landing pages, institutional websites, small systems, simple APIs, and traditional front-end work.
- Preserve the `app/`, `system/`, `public/`, and `storage/` structure, including split language roots under `app/languages/` and `system/languages/`.
- Use `App\...` namespaces for application code and `System\...` namespaces for framework code.
- Controllers must return `Psr\Http\Message\ResponseInterface`, through `System\Core\Response` or `response_*()` helpers when helpers are enabled.
- Views should focus on presentation; do not put business logic in them.
- PHP view page and template names must be developer-owned relative paths. `System\Core\View` rejects absolute paths, traversal, null bytes, unexpected extensions, and files outside the expected view directories before including PHP files.
- Models should concentrate data access, queries, and simple persistence rules.
- Procedural helpers are optional convenience APIs. Framework runtime code should use static `System\...` classes directly, while app code may use helpers when autoloaded.
- Before creating a new function, class, or dependency, look for an existing helper, core class, or internal pattern.
- Do not convert the project to Laravel, Symfony, Slim, React, Vue, Angular, or another larger framework without an explicit request.
- Vue/Vite support is optional and should only be used when a route explicitly calls the Vue renderer.
- Prefer Bootstrap 5 and vanilla JavaScript for traditional front-end work.
- Use prepared statements whenever SQL is involved; never concatenate user input directly into queries.
- Preserve `BASE_PATH` compatibility; assets should use `path_base_public()` and absolute URLs should use `site_url()`.
- `site_url()` / `Path::siteURL()` derives protocol and host from the request. `X-Forwarded-Host` / `X-Forwarded-Proto` are honoured only when `REMOTE_ADDR` is listed in `TRUSTED_PROXIES`; the `Host` header itself is still whatever the client sent, so an app that needs a fixed canonical origin should configure it in its own `.env`.
- `RouterLoader::dispatch()` answers `HEAD` through the matching `GET` route (same headers, body discarded); routes do not need a separate `HEAD` definition.
- In route files, declare root handlers with `/`; `RouterLoader` also accepts the exact prefixed URL without a trailing slash for that root route.
- Translatable UI text should live in `app/languages/*` or `system/languages/*` and be consumed through `System\Core\Language::get()` or `lg()` when helpers are enabled.
- APIs must not use sessions; the bootstrap uses `NULLHandler` for API requests.
- Session storage drivers are selected by the framework, but cookie hardening flags still depend on PHP/default deployment configuration unless explicitly added in code.
- The system web area (`/web-system`) is open in development only; elsewhere `System\Middlewares\SystemWebAuth` requires `SYSTEM_TOKEN` and answers 404 without it.
- The system documentation home includes a dangerous app cleanup action. It is irreversible, currently blocked by a direct manual safety `return` in `System\Controllers\Maintenance::cleanApp()`, and should only be unblocked to reset the app skeleton for a fresh project. Its nonce is signed with `SYSTEM_TOKEN` (then `APP_KEY`) read through `Globals::env()`, and requests without `Origin`/`Referer` are refused.
- Deliver small, testable changes that are consistent with the project's own MVC style.

## Stack and Dependencies

- **PHP 8.2+**
- **Composer**
- **PSR-4** autoloading for the `App\` and `System\` namespaces
- **miladrahimi/phprouter** for routing
- **Laminas Diactoros** for PSR-7 responses
- **Laminas HttpHandlerRunner** is installed as a dependency, but the current bootstrap still emits exception responses manually and relies on the router dispatch flow for normal requests.
- **vlucas/phpdotenv** for environment variables
- **PDO** for database access

## Main Structure

```text
app/
  Bootable/         Classes executed during bootstrap when they implement IBootable
  Controllers/      Application controllers, namespace App\Controllers
  Middlewares/      Route and route-group middlewares
  Models/           Application models, namespace App\Models
  helpers/          App-specific helpers
  languages/        Application-owned translation JSON files, exposed as app.*
  routes/           Application web.php and api.php route files
  views/
    pages/          Application page views
    templates/      Application templates/layouts
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

## Bootstrap Summary

Application entry point:

```text
public/index.php
```

Essential flow:

1. Load `../vendor/autoload.php`.
2. Include `system/includes/error_handlers.php`.
3. Run `Globals::loadEnv()` and read `.env`.
4. Detect app API, system API, and system web requests with `Globals`.
5. Configure error display according to `APP_ENV`.
6. Load system helpers according to `SYSTEM_HELPERS_AUTOLOAD` and app helpers according to `APP_HELPERS_AUTOLOAD`.
7. Configure sessions: web requests use normal handlers; app and system API requests disable cookies and use `System\Session\NULLHandler`.
8. Automatically connect to the default database when `DB_DRIVER` is valid; named database connections are opened lazily.
9. Execute bootables in `app/Bootable`.
10. Load `system/routes/api.php` under `/api-system`, `system/routes/web.php` under `/web-system`, `app/routes/api.php` under `/api`, or `app/routes/web.php` for normal app web routes.
11. Dispatch the route through `RouterLoader` / `router_loader_dispatch()`. Exact prefixed root requests such as `/web-system` can match root routes declared as `/`, while non-root trailing slash behavior remains exact.
12. Return HTML 404 for missing routes and HTML 500 for general errors; the daily log is always written, details are shown only outside production.

## Main `.env` Variables

```dotenv
APP_ENV=development
BASE_PATH=/php-mini-mvc
TRUSTED_PROXIES=
DEFAULT_LANGUAGE=en
SYSTEM_TOKEN=
SYSTEM_HELPERS_AUTOLOAD=true
APP_HELPERS_AUTOLOAD=true
APP_KEY=
VITE_DEV_SERVER=

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

Quick rules:

- `APP_ENV`: `production`, `development`, or `testing`; fallback should be treated as `production`.
- `BASE_PATH`: required when the app runs from a subdirectory.
- `TRUSTED_PROXIES`: comma-separated IPs or CIDR blocks of reverse proxies whose `X-Forwarded-Host` / `X-Forwarded-Proto` may be trusted by `Path::siteURL()`; `*` trusts every connection; empty ignores forwarded headers.
- `DEFAULT_LANGUAGE`: default language for translations.
- `SYSTEM_TOKEN`: fixed owner-defined token for protected system routes. `System\Middlewares\SystemI18nAuth` enforces it for `/api-system/i18n` (empty disables that endpoint) and `System\Middlewares\SystemWebAuth` enforces it for `/web-system` outside development (empty means 404 there). Vue pages that fetch i18n directly receive it in browser boot data, so do not use it to protect private user data.
- `SYSTEM_HELPERS_AUTOLOAD`: `true` for all system helpers, a specific list such as `['response','view.php']`, or disabled values such as `false`, `0`, `none`, `off`, `no`, or empty.
- `APP_HELPERS_AUTOLOAD`: `true` for all app helpers, a specific list, or disabled values such as `false`, `0`, `none`, `off`, `no`, or empty.
- `APP_KEY`: optional project secret. Current framework code uses it only as a fallback signer for the dangerous cleanup nonce when `SYSTEM_TOKEN` is empty, before falling back to a path-derived development value. Both are read through `Globals::env()`.
- `VITE_DEV_SERVER`: optional Vue-only development URL. When set, the PHP template loads Vite client and Vue entrypoints from that server instead of `public/build/.vite/manifest.json`.
- `SESSION_DRIVER`: `files`, `db`, or `none`.
- `SESSION_DB`: optional named connection for `SESSION_DRIVER=db`; blank uses the default `DB_*` connection.
- `SESSION_ENCRYPT_KEY`: optional for `SESSION_DRIVER=db`; when filled, it must have at least 32 random characters and stores DB session payloads with a versioned authenticated encryption format. Older deterministic AES-CBC encrypted session rows are not compatible and should be cleared after upgrading.
- `DB_DRIVER`: `mysql`, `pgsql`, or `none` for the default database connection.
- `DB_*_<SUFFIX>`: optional named database connections. The lowercase suffix is the connection key, such as `app`, `auth`, or `robot`. Required fields per suffix are `DRIVER`, `HOST`, `NAME`, and `USER`; `PASS`, `PORT`, and `CHARSET` are optional.

## Helper Functions by Area

| Area | Helpers |
| --- | --- |
| Database config | `database_driver`, `database_is`, `database_is_mysql`, `database_is_postgres`, `database_is_none`, `database_config_permitted_drivers`, `database_config_normalize_connection_name`, `database_config_default_port`, `database_config_connection`, `database_config_connections`, `database_config_configure`, `database_config_forget_connection`, `database_connection_names`, `database_has_connection` |
| Database core | `database_connect`, `database_configure`, `database_forget_connection`, `database_select`, `database_select_row`, `database_statement`, `database_get_last_inserted_id`, `database_is_in_transaction`, `database_start_transaction`, `database_commit_transaction`, `database_rollback_transaction`, `database_disconnect` |
| Environment | `environment_type`, `environment_is`, `environment_is_production`, `environment_is_development`, `environment_is_testing` |
| Form | `form_validator`, `form_validator_register_rule` |
| Globals | `globals_get`, `globals_add`, `globals_merge`, `globals_forget`, `globals_forget_many`, `globals_reset`, `globals_load_env`, `globals_env`, `globals_get_api_prefix`, `globals_is_api_request`, `globals_get_system_web_prefix`, `globals_get_system_api_prefix`, `globals_is_system_web_request`, `globals_is_system_api_request` |
| Languages | `lg`, `language_get`, `language_get_by_prefix`, `language_normalize_prefix`, `language_load`, `ld`, `language_detect`, `language_current`, `language_default` |
| Path | `path_root`, `path_app`, `path_app_bootable`, `path_app_helpers`, `path_app_languages`, `path_app_routes`, `path_app_middlewares`, `path_app_controllers`, `path_app_models`, `path_app_views`, `path_app_views_pages`, `path_app_views_templates`, `path_system`, `path_system_interfaces`, `path_system_helpers`, `path_system_languages`, `path_system_routes`, `path_system_middlewares`, `path_system_controllers`, `path_system_models`, `path_system_views`, `path_system_views_pages`, `path_system_views_templates`, `path_system_includes`, `path_public`, `path_storage`, `path_storage_sessions`, `path_storage_logs`, `path_languages`, `path_base`, `path_base_public`, `site_url` |
| PHP autoload | `php_autoload_from`, `php_autoload_boot` |
| Response | `response_redirect`, `response_html`, `response_text`, `response_json`, `response_xml`, `response_file` |
| Router loader | `router_loader_load`, `router_loader_load_with_prefix`, `router_loader_load_system`, `router_loader_load_system_with_prefix`, `router_loader_dispatch` |
| Session | `session_start_safe`, `session_has`, `session_get`, `session_set`, `session_set_many`, `session_forget`, `session_clear`, `session_save`, `session_destroy_safe`, `session_regenerate`, `session_driver`, `session_is`, `session_is_files`, `session_is_db`, `session_is_none` |
| View | `view_share`, `view_share_many`, `view_forget`, `view_forget_many`, `view_clear`, `view_set_template`, `view_get_template`, `view_render_page`, `view_render_system_page`, `view_render_html`, `view_render_vue`, `view_globals` |

## Quick Decisions

| Need | Use |
| --- | --- |
| Render a page | `System\Core\View::render_page()` + `System\Core\Response::html()`; helper shortcuts may be used when enabled |
| Render a system page | `System\Core\View::render_system_page()` + `System\Core\Response::html()` |
| Render an optional Vue page | `System\Core\View::render_vue()` + `System\Core\Response::html()` |
| Render Vue with MVC translations | `view_render_vue('users/Profile', $props, null, ['app.pages.users'])` |
| Return JSON | `System\Core\Response::json()` |
| Redirect | `System\Core\Response::redirect()` |
| Open framework documentation | `/web-system` |
| Generate an absolute URL | `System\Core\Path::siteURL()` |
| Generate an asset path | `System\Core\Path::basePathPublic()` |
| Fetch a translation | `System\Core\Language::get()` |
| Fetch translations by prefix | `System\Core\Language::getByPrefix()` |
| Normalize a translation prefix | `System\Core\Language::normalizePrefix()` |
| Write framework runtime code | Static `System\...` classes directly |
| Reset app example files for a fresh project | `/web-system` -> `Remove and Clean MVC` |
| Query multiple rows | `database_select()` |
| Query one row | `database_select_row()` |
| Run insert/update/delete | `database_statement()` |
| Use a named DB connection | `database_select($sql, $params, null, 'auth')` |
| Register a runtime DB connection | `database_configure('reporting', $config)` |
| Validate a form | `form_validator()` |
| Read web session data | `session_get()` / `session_has()` |
| Share a global variable with views | `view_share()` |
| Lightweight global initialization | Class in `app/Bootable` implementing `IBootable` |

## Creating Vue Pages In This MVC

Vue pages are opt-in. A route must explicitly select a template prepared for Vue and then call `View::render_vue()` or `view_render_vue()`.

Before creating page files, understand the framework and resource pieces involved:

```text
system/Core/View.php
system/helpers/view.php
system/views/templates/
resources/vue/App.vue
resources/vue/main.js
resources/vue/pages/
```

- `System\Core\View::render_vue()` prepares a Vue boot payload with `page`, `props`, `meta`, and optional `i18n` information.
- `view_render_vue()` is only a helper shortcut for the same renderer when helpers are enabled.
- `system/views/templates/` contains framework template examples for server-rendered view shells. A Vue-ready template must follow the same server-rendered shell idea, but it also needs the Vue mount and boot payload contract.
- `resources/vue/main.js` is the default Vite entrypoint used when the renderer entrypoint argument is `null`.
- `resources/vue/App.vue` receives the boot payload and resolves the requested page with `import.meta.glob('./pages/**/*.vue')`.
- `resources/vue/pages/` stores Vue single-file components that can be selected by developer-owned route or controller code.

Generic flow for a new Vue page:

1. Decide whether the route really needs Vue. Normal PHP pages should stay on the PHP view renderer.
2. Create the Vue SFC under `resources/vue/pages/`, for example `resources/vue/pages/examples/Example.vue`.
3. Keep the component focused. Use `<script setup>`, pass data as props, and derive display values with `computed` instead of doing work directly in the template.
4. Make sure the selected PHP template handles Vue boot data. It must print a `#php-mini-mvc-vue` mount element and a JSON script with `page`, `props`, `meta`, and `i18n` under `#php-mini-mvc-vue-data`.
5. In developer-owned route or controller code, select a Vue-ready template if the current template is not Vue-ready.
6. Return `Response::html(View::render_vue('examples/Example', $props))`.
7. Do not build the Vue page name from request, query-string, database, log, upload, or other user-controlled data. Page names must be explicit route/controller decisions.
8. Pass only server-owned, intentionally public props to the browser. Treat database text as untrusted and render it through Vue interpolation, not `v-html`.
9. Run `npm run build` for manifest-based production rendering, or configure `VITE_DEV_SERVER` during development.

Theoretical route/controller example:

```php
use System\Core\Response;
use System\Core\View;

$router->get('/example/vue', static function () {
    View::setTemplate('vue-ready-template');

    return Response::html(View::render_vue('examples/Example', [
        'title' => 'Example Vue page',
    ]));
});
```

The shared `resources/vue/App.vue` uses `import.meta.glob('./pages/**/*.vue')`, so the route argument `examples/Example` resolves to:

```text
resources/vue/pages/examples/Example.vue
```

## Document Summary

| Name | Description | Document |
| --- | --- | --- |
| Architecture and bootstrap | Purpose, stack, structure, request lifecycle, and global conventions. | [01-architecture-bootstrap.md](mvc-references/01-architecture-bootstrap.md) |
| Configuration, routes, and URLs | `.env`, web/API routes, `BASE_PATH`, assets, and URL generation. | [02-configuration-routes-urls.md](mvc-references/02-configuration-routes-urls.md) |
| MVC layers | Controllers, models, views, templates, and the flow for creating pages. | [03-mvc-layers.md](mvc-references/03-mvc-layers.md) |
| Languages and dynamic documentation | Translation system, split app/system language roots, source prefixes, and the home page as documentation. | [04-languages.md](mvc-references/04-languages.md) |
| Database, session, and forms | PDO, `database_*` helpers, session drivers, and `FormValidator`. | [05-database-session-forms.md](mvc-references/05-database-session-forms.md) |
| Responses, middlewares, and bootables | `response_*` helpers, middlewares, and lightweight global initialization. | [06-responses-middlewares-bootables.md](mvc-references/06-responses-middlewares-bootables.md) |
| Helper and system class reference | Helper index plus per-class files with namespaces, static method signatures, helper signatures, accepted arguments, and usage examples. | [07-helper-reference.md](mvc-references/07-helper-reference.md) |
| Workflows | Checklists for new pages, APIs, and new projects. | [08-workflows.md](mvc-references/08-workflows.md) |
| Errors and cautions | Logs, handlers, known risks, and specific rules for agents. | [09-errors-cautions.md](mvc-references/09-errors-cautions.md) |
| Optional Vue and Vite support | Optional Vue renderer workflow, route examples, props, entrypoints, build output, and `BASE_PATH` cautions. | [10-vue-vite.md](mvc-references/10-vue-vite.md) |

## Home as Dynamic Documentation

The view:

```text
system/views/pages/home.php
```

works as dynamic framework documentation.

It:

- uses `System\Core\Language::get(...)` to fetch text from `system/languages/doc/*` with `system.doc.*` keys;
- shows a framework summary;
- documents helper loading through `SYSTEM_HELPERS_AUTOLOAD` and `APP_HELPERS_AUTOLOAD`;
- exposes the `Remove and Clean MVC` maintenance button, protected by `SystemWebAuth` outside development, a short-lived nonce, a same-origin check that refuses requests without `Origin`/`Referer`, SweetAlert confirmation (pinned version with SRI), and a direct manual safety `return` that must be removed in `System\Controllers\Maintenance::cleanApp()` before intentional use;
- defines a `$docs` array with classes, methods, examples, and descriptions;
- renders those entries as HTML `<details>` sections.

When adding new public framework classes, update:

```text
system/views/pages/home.php
system/languages/doc/en.json
system/languages/doc/pt-br.json
system/languages/doc/es.json
```

The app root `/` redirects to `/web-system`. With `BASE_PATH=/php-mini-mvc`, the documentation URL is `/php-mini-mvc/web-system`.

The system documentation template sends crawler-blocking directives through `robots`, `googlebot`, and `bingbot` meta tags, and `System\Controllers\Home` adds an `X-Robots-Tag` header with `noindex`, `nofollow`, `noarchive`, `nosnippet`, and `noimageindex`.

The dangerous cleanup action is handled by `System\Controllers\Maintenance` at the system web route `/web-system/maintenance/clean-app`. It is blocked by default by a direct safety `return` inside `cleanApp()`. To run it intentionally, remove that return manually before triggering the action. When unblocked, it deletes only the explicit target contents documented in [08-workflows.md](mvc-references/08-workflows.md), rewrites app routes, and keeps system documentation available at `/web-system`.

## Main Principle

This project should remain a **simple, predictable, direct mini-framework**.

When implementing any change, prefer the smallest correct change, keep the existing pattern, and avoid unnecessary abstractions.
