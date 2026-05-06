# System\Config\Session

Source: `system/Config/Session.php`  
Helper source: `system/helpers/session.php`  
Namespace: `System\Config`

Resolves the configured session driver from `SESSION_DRIVER`. Invalid or missing values fall back to `none`.

## Static Usage

```php
use System\Config\Session;

if (Session::isFiles()) {
    // Native file session handler will be used.
}
```

## Helper Usage

```php
if (session_is_files()) {
    // Native file session handler will be used.
}
```

## Method And Helper Signatures

| Static method | Helper | Accepts | Returns |
| --- | --- | --- | --- |
| `Session::env(): string` | `session_driver(): string` | No arguments. Reads `SESSION_DRIVER` from the loaded env store. | `files`, `db`, or `none`. |
| `Session::is(string $env): bool` | `session_is(string $env): bool` | Session driver name to compare. Expected values are `files`, `db`, or `none`. | `true` when the active driver matches. |
| `Session::isFiles(): bool` | `session_is_files(): bool` | No arguments. | `true` when native file-backed sessions are configured. |
| `Session::isDB(): bool` | `session_is_db(): bool` | No arguments. | `true` when database-backed sessions are configured. |
| `Session::isNone(): bool` | `session_is_none(): bool` | No arguments. | `true` when sessions are disabled or invalid. |

## Notes

- This class only resolves session configuration. Use `System\Core\Session` or `session_*` helpers for reading and writing session data.
- API requests use `System\Session\NULLHandler` during bootstrap even when a web session driver is configured.
