# System\Config\Globals

Source: `system/Config/Globals.php`  
Helper source: `system/helpers/globals.php`  
Namespace: `System\Config`

Stores runtime configuration, loads `.env` values through `vlucas/phpdotenv`, and detects API requests.

## Static Usage

```php
use System\Config\Globals;

Globals::loadEnv();
$basePath = Globals::env('BASE_PATH');
Globals::add('current_page', 'home');
```

## Helper Usage

```php
globals_load_env();
$basePath = globals_env('BASE_PATH');
globals_add('current_page', 'home');
```

## Method And Helper Signatures

| Static method | Helper | Accepts | Returns |
| --- | --- | --- | --- |
| `Globals::get(?string $key = null, mixed $default = null): mixed` | `globals_get(?string $key = null, mixed $default = null): mixed` | Optional config key and fallback value. | Full config array when `$key` is null, the stored value, or `$default`. |
| `Globals::add(string $key, mixed $value): void` | `globals_add(string $key, mixed $value): void` | Config key and value to add or overwrite. | Nothing. |
| `Globals::merge(array $config): void` | `globals_merge(array $config): void` | Associative array of config values. | Nothing. |
| `Globals::forget(string $key): void` | `globals_forget(string $key): void` | Config key to remove. | Nothing. |
| `Globals::forgetMany(array $keys): void` | `globals_forget_many(array $config): void` | Array of config keys to remove. | Nothing. |
| `Globals::reset(): void` | `globals_reset(): void` | No arguments. Clears runtime config and reloads `.env`. | Nothing. |
| `Globals::loadEnv(): void` | `globals_load_env(): void` | No arguments. Loads `.env` once. | Nothing. |
| `Globals::env(?string $key = null): array|string|null` | `globals_env(?string $key = null): array|string|null` | Optional env key. | Full env array, one string value, or `null`. |
| `Globals::getApiPrefix(): string` | `globals_get_api_prefix(): string` | No arguments. | The API prefix, currently `/api`. |
| `Globals::isApiRequest(): bool` | `globals_is_api_request(): bool` | No arguments. Reads `REQUEST_URI` and `BASE_PATH`. | `true` when the current request targets the API prefix. |

## Notes

- `loadEnv()` is idempotent while the internal env cache is populated.
- `reset()` clears the cache and reloads `.env`; use it carefully in tests or bootstrap code.
- `isApiRequest()` strips `BASE_PATH` before checking the `/api` prefix.
