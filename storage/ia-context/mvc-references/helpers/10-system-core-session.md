# System\Core\Session

Source: `system/Core/Session.php`  
Helper source: `system/helpers/session.php`  
Namespace: `System\Core`

Starts, reads, writes, clears, saves, destroys, and regenerates web session data.

## Static Usage

```php
use System\Core\Session;

Session::set('user_id', 10);
$userId = Session::get('user_id');
```

## Helper Usage

```php
session_set('user_id', 10);
$userId = session_get('user_id');
```

## Method And Helper Signatures

| Static method | Helper | Accepts | Returns |
| --- | --- | --- | --- |
| `Session::start(): void` | `session_start_safe(): void` | No arguments. Starts the PHP session when allowed. | Nothing. Throws if session driver is `none` or request is API. |
| `Session::has(string $key): bool` | `session_has(string $key): bool` | Session key. | `true` when `$_SESSION[$key]` is set. |
| `Session::get(string $key, mixed $default = null): mixed` | `session_get(string $key, mixed $default = null): mixed` | Session key and fallback value. | Stored value or fallback. |
| `Session::set(string $key, mixed $value): void` | `session_set(string $key, mixed $value): void` | Session key and value. | Nothing. |
| `Session::setMany(array $items): void` | `session_set_many(array $items): void` | Associative array of key/value pairs. | Nothing. |
| `Session::forget(string $key): void` | `session_forget(string $key): void` | Session key to unset. | Nothing. |
| `Session::clear(): void` | `session_clear(): void` | No arguments. | Clears `$_SESSION` but keeps the session alive. |
| `Session::save(): void` | `session_save(): void` | No arguments. | Writes and closes the active session if one is open. |
| `Session::destroy(): void` | `session_destroy_safe(): void` | No arguments. | Clears and destroys the active session if one is open. |
| `Session::regenerate(bool $deleteOldSession = true): void` | `session_regenerate(bool $deleteOldSession = true): void` | Whether PHP should delete the old session ID. | Nothing. |

## Notes

- Most methods call `Session::start()` first, so they can throw when sessions are disabled or the request is an API request.
- Session driver checks such as `session_is_db()` belong to `System\Config\Session`; see [05-system-config-session.md](05-system-config-session.md).
