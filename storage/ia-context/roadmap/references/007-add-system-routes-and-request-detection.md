# Add System Routes and Request Detection

## Goal

Add first-class route files for the framework under `system/routes/`, with separate web and API prefixes for system-owned routes.

## Required Context

- Read this plan first.
- Then read `storage/ia-context/mvc.md` for the project conventions needed to implement the task.
- Also inspect:
  - `public/index.php`
  - `system/Config/Globals.php`
  - `system/helpers/globals.php`
  - `system/Core/RouterLoader.php`
  - `app/routes/web.php`
  - `app/routes/api.php`
  - `storage/ia-context/mvc-references/02-configuration-routes-urls.md`

## Required Prefixes

System routes must use these prefixes:

```text
/web-system
/api-system
```

With `BASE_PATH=/php-mini-mvc`, final URLs must be:

```text
/php-mini-mvc/web-system
/php-mini-mvc/api-system
```

## Required Files

Create:

```text
system/routes/web.php
system/routes/api.php
```

They should work like `app/routes/web.php` and `app/routes/api.php`, but for framework/system routes.

## Request Detection API

Add methods to `System\Config\Globals` near the existing API route detection logic:

- `getSystemWebPrefix(): string`
- `getSystemApiPrefix(): string`
- `isSystemWebRequest(): bool`
- `isSystemApiRequest(): bool`

Keep the existing app API behavior:

- `getApiPrefix(): string`
- `isApiRequest(): bool`

Add matching helpers in `system/helpers/globals.php`:

- `globals_get_system_web_prefix()`
- `globals_get_system_api_prefix()`
- `globals_is_system_web_request()`
- `globals_is_system_api_request()`

## Router Loading Plan

1. Extend `RouterLoader` so it can load route files from `system/routes/` as well as `app/routes/`.
2. Avoid duplicating routing logic.
3. Keep `load()` and `loadWithPrefix()` behavior for app routes.
4. Add explicit system route loading methods, or add a safe source argument, for example:
   - `loadSystem(string $file)`
   - `loadSystemWithPrefix(string $prefix, string $file)`
5. Preserve `BASE_PATH` behavior for both app and system route groups.
6. Reject or normalize route file names the same way current route loading does.

## Bootstrap Plan

Update `public/index.php` so route loading can distinguish:

1. system API route: `/api-system`
2. system web route: `/web-system`
3. app API route: `/api`
4. app web route: normal app web routes

Suggested priority:

```php
if (Globals::isSystemApiRequest()) {
    RouterLoader::loadSystemWithPrefix(Globals::getSystemApiPrefix(), 'api');
} elseif (Globals::isSystemWebRequest()) {
    RouterLoader::loadSystemWithPrefix(Globals::getSystemWebPrefix(), 'web');
} elseif (Globals::isApiRequest()) {
    RouterLoader::loadWithPrefix(Globals::getApiPrefix(), 'api');
} else {
    RouterLoader::load('web');
}
```

Session handling should continue to disable sessions for API requests. Include system API requests in that decision.

## Acceptance Criteria

- `system/routes/web.php` and `system/routes/api.php` exist.
- `/web-system` loads system web routes.
- `/api-system` loads system API routes.
- `BASE_PATH` works for system and app routes.
- Existing app `/api` and web routes continue to work.
- System API requests are treated as API requests for session handling.
- Route loading code avoids duplicated app/system include logic where practical.
