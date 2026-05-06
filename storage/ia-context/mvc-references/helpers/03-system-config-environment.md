# System\Config\Environment

Source: `system/Config/Environment.php`  
Helper source: `system/helpers/environment.php`  
Namespace: `System\Config`

Resolves the runtime environment from `APP_ENV`. Invalid or missing values fall back to `production`.

## Static Usage

```php
use System\Config\Environment;

if (Environment::isDevelopment()) {
    ini_set('display_errors', '1');
}
```

## Helper Usage

```php
if (environment_is_development()) {
    ini_set('display_errors', '1');
}
```

## Method And Helper Signatures

| Static method | Helper | Accepts | Returns |
| --- | --- | --- | --- |
| `Environment::env(): string` | `environment_type(): string` | No arguments. Reads `APP_ENV` from the loaded env store. | `production`, `development`, or `testing`. |
| `Environment::is(string $env): bool` | `environment_is(string $env): bool` | Environment name to compare. | `true` when the active environment matches. |
| `Environment::isProduction(): bool` | `environment_is_production(): bool` | No arguments. | `true` when `APP_ENV` resolves to `production`. |
| `Environment::isDevelopment(): bool` | `environment_is_development(): bool` | No arguments. | `true` when `APP_ENV` resolves to `development`. |
| `Environment::isTesting(): bool` | `environment_is_testing(): bool` | No arguments. | `true` when `APP_ENV` resolves to `testing`. |

## Notes

- Only `production`, `development`, and `testing` are accepted.
- Treat unknown environments as production behavior because that is the class fallback.
