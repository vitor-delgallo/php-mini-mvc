# System\Core\RouterLoader

Source: `system/Core/RouterLoader.php`  
Helper source: none  
Namespace: `System\Core`

Initializes the shared router, loads route files, applies prefixes, and dispatches the current request.

## Static Usage

```php
use System\Core\RouterLoader;

RouterLoader::load('web');
RouterLoader::loadWithPrefix('/api', 'api');
RouterLoader::dispatch();
```

## Helper Usage

There is no procedural helper for this class. It is used directly by bootstrap code.

## Method Signatures

| Static method | Accepts | Returns |
| --- | --- | --- |
| `RouterLoader::load(string $file): void` | Route filename relative to `app/routes`, with or without `.php`. Applies `Path::basePath()` when present. | Nothing. |
| `RouterLoader::loadWithPrefix(string $prefix, string $file): void` | Route group prefix and route filename relative to `app/routes`, with or without `.php`. | Nothing. |
| `RouterLoader::dispatch(): void` | No arguments. Dispatches the current request through the shared router. | Nothing. Can throw router exceptions. |

## Notes

- Route files receive `$router` in local scope.
- `loadWithPrefix()` normalizes the prefix with one leading slash and no trailing slash.
- `public/index.php` loads `web` routes for normal requests and `api` routes under `Globals::getApiPrefix()` for API requests.
