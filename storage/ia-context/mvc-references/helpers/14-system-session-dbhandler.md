# System\Session\DBHandler

Source: `system/Session/DBHandler.php`  
Helper source: none  
Namespace: `System\Session`

Implements `SessionHandlerInterface` and stores PHP sessions in the configured database.

## Object Usage

```php
use System\Session\DBHandler;

$handler = new DBHandler($pdo, 'app_', $encryptionKey);
session_set_save_handler($handler, true);
```

## Helper Usage

There is no procedural helper for this class. Bootstrap creates it from `system/includes/session_handlers.php` when `SESSION_DRIVER=db`.

## Constructor And Method Signatures

| Method | Accepts | Returns |
| --- | --- | --- |
| `__construct(PDO $pdo, ?string $prefix = null, ?string $encryptionKey = null)` | PDO connection, optional session ID prefix, optional AES-256-CBC encryption key. | A session handler instance. Also creates the `sessions` table if needed. |
| `open($path, $name): bool` | Native PHP session open arguments. Not used by this implementation. | `true`. |
| `close(): bool` | No arguments. | `true`. |
| `read($id): string` | Session ID without prefix. | Session payload string, decrypted when an encryption key is configured. |
| `write($id, $data): bool` | Session ID without prefix and serialized session payload. | `true` when the upsert succeeds. |
| `destroy($id): bool` | Session ID without prefix. | `true` when deletion executes. |
| `gc($max_lifetime): int|false` | Maximum lifetime in seconds. | Number of deleted rows or `false`. |

## Storage Rules

- Uses table name `sessions`.
- Applies `$prefix` to stored session IDs.
- Stores `data`, `ip`, `user_agent`, and `updated_at`.
- Uses MySQL `ON DUPLICATE KEY UPDATE` or PostgreSQL `ON CONFLICT`.
- Optional encryption uses `openssl_encrypt()` with `aes-256-cbc` and the first 16 characters of the key as IV.

## Notes

- The constructor calls `ensureTableExists()`, so database permissions must allow table creation.
- The PostgreSQL garbage collector SQL currently uses an interval placeholder inside a quoted interval expression; verify it before relying on PostgreSQL GC behavior.
