# System\Core\RouterLoader

Source: `system/Core/RouterLoader.php`  
Helper source: `system/helpers/router_loader.php`  
Namespace: `System\Core`

Initializes the shared router, loads route files, applies prefixes, and dispatches the current request.

## Static Usage

```php
use System\Core\RouterLoader;

RouterLoader::load('web');
RouterLoader::loadWithPrefix('/api', 'api');
RouterLoader::loadSystemWithPrefix('/web-system', 'web');
RouterLoader::dispatch();
```

## Helper Usage

```php
router_loader_load('web');
router_loader_load_with_prefix('/api', 'api');
router_loader_load_system_with_prefix('/web-system', 'web');
router_loader_dispatch();
```

## Method And Helper Signatures

| Static method | Helper | Accepts | Returns |
| --- | --- | --- | --- |
| `RouterLoader::load(string $file): void` | `router_loader_load(string $file): void` | Route filename relative to `app/routes`, with or without `.php`. Applies `Path::basePath()` when present. | Nothing. |
| `RouterLoader::loadWithPrefix(string $prefix, string $file): void` | `router_loader_load_with_prefix(string $prefix, string $file): void` | Route group prefix and route filename relative to `app/routes`, with or without `.php`. | Nothing. |
| `RouterLoader::loadSystem(string $file): void` | `router_loader_load_system(string $file): void` | Route filename relative to `system/routes`, with or without `.php`. Applies `Path::basePath()` when present. | Nothing. |
| `RouterLoader::loadSystemWithPrefix(string $prefix, string $file): void` | `router_loader_load_system_with_prefix(string $prefix, string $file): void` | Route group prefix and route filename relative to `system/routes`, with or without `.php`. | Nothing. |
| `RouterLoader::dispatch(): void` | `router_loader_dispatch(): void` | No arguments. Dispatches the current request through the shared router. | Nothing. Can throw router exceptions. |

## Notes

- Route files receive `$router` in local scope.
- `loadWithPrefix()` normalizes the prefix with one leading slash and no trailing slash.
- Root handlers should be declared with `/`. For prefixed route files, `RouterLoader` also lets the exact prefix URL without a trailing slash match that root route when no explicit `''` route exists.
- This root-prefix normalization is intentionally narrow: non-root routes such as `/api-system/i18n/` do not match `/api-system/i18n` unless declared separately.
- `public/index.php` loads system API routes under `/api-system`, system web routes under `/web-system`, app API routes under `/api`, or app web routes for normal app web requests.
- The helpers are direct wrappers around the static methods and are intended for route/bootstrap code that prefers the procedural helper API.
