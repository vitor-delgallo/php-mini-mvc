# Normalize Prefixed Root Routes

## Goal

Allow a route declared as `/` inside a prefixed route file to also match the prefix URL without a trailing slash.

After this task, framework route files should be able to define only:

```php
$router->get('/', $handler);
```

and have both URLs work:

```text
/web-system
/web-system/
/api-system
/api-system/
```

## Required Context

- Read this plan first.
- Then read `storage/ia-context/mvc.md` for project conventions.
- Also inspect:
  - `system/Core/RouterLoader.php`
  - `system/routes/web.php`
  - `system/routes/api.php`
  - `app/routes/web.php`
  - `app/routes/api.php`
  - `storage/ia-context/mvc-references/02-configuration-routes-urls.md`
  - `storage/ia-context/mvc-references/helpers/13-system-core-routerloader.md`

## Problem

`miladrahimi/phprouter` compares route paths exactly.

Inside a route group with prefix `/api-system`, these two declarations register different final paths:

```php
$router->get('', $handler);  // /api-system
$router->get('/', $handler); // /api-system/
```

This forces duplicate route declarations for root handlers. The MVC should hide that detail so route files stay clean and predictable.

## Implementation Plan

1. Do not edit files under `vendor/`.
2. Update `System\Core\RouterLoader` so it tracks loaded root route prefixes, including:
   - `Path::basePath()` for unprefixed route files when a base path is configured;
   - `Path::basePath() . $prefix` for route files loaded with `loadWithPrefix()` or `loadSystemWithPrefix()`.
3. Before dispatching, normalize only exact root-prefix requests:
   - if the request path exactly matches a tracked prefix;
   - and no exact route exists for that path;
   - and a route exists for the same path with a trailing `/`;
   - temporarily dispatch the request as the trailing-slash path while preserving query string.
4. Preserve existing behavior for routes explicitly registered with `''`.
5. Do not broaden general trailing-slash matching. For example, `/api-system/i18n/` must not match `/api-system/i18n` unless that route is explicitly supported.
6. Remove duplicate root route declarations from:
   - `system/routes/web.php`
   - `system/routes/api.php`
7. Prefer `/` as the documented root route style.

## Documentation Updates

Update:

- `storage/ia-context/mvc.md`
- `storage/ia-context/mvc-references/02-configuration-routes-urls.md`
- `storage/ia-context/mvc-references/helpers/13-system-core-routerloader.md`

Document that:

- route root handlers should be declared with `/`;
- the loader accepts both the prefix URL and prefix URL with trailing slash for root routes;
- this normalization is limited to exact root-prefix routes and does not apply to every route.

## Acceptance Criteria

- `system/routes/web.php` defines the system home only once with `/`.
- `system/routes/api.php` defines the system API home only once with `/`.
- `/web-system` and `/web-system/` both serve the documentation home.
- `/api-system` and `/api-system/` both serve the system API home JSON.
- Non-root routes are not accidentally widened by trailing slash normalization.
- Existing `BASE_PATH` behavior is preserved.
- `php -l` passes for changed PHP files.
- Playwright verifies the system documentation home in the browser.
- A lightweight HTTP or PHP dispatch check verifies both `/api-system` variants.
- The corresponding README item is marked `[CONCLUDED]` only after implementation and verification.
