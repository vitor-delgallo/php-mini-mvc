# System\Session\NULLHandler

Source: `system/Session/NULLHandler.php`  
Helper source: none  
Namespace: `System\Session`

Implements `SessionHandlerInterface` with no persistence. Used when sessions are disabled or for API request handling.

## Object Usage

```php
use System\Session\NULLHandler;

session_set_save_handler(new NULLHandler(), true);
```

## Helper Usage

There is no procedural helper for this class. Bootstrap registers it directly.

## Method Signatures

| Method | Accepts | Returns |
| --- | --- | --- |
| `open($path, $name): bool` | Native PHP session open arguments. | `true`. |
| `close(): bool` | No arguments. | `true`. |
| `read($id): string` | Session ID. | Empty string. |
| `write($id, $data): bool` | Session ID and serialized session payload. | `true`. |
| `destroy($id): bool` | Session ID. | `true`. |
| `gc($max_lifetime): int|false` | Maximum lifetime in seconds. | `false`. |

## Notes

- API bootstrap also disables cookies and trans SID before registering this handler.
- Use this handler only when session reads/writes should be ignored.
