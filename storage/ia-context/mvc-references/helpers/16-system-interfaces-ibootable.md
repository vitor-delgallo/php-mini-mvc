# System\Interfaces\IBootable

Source: `system/Interfaces/IBootable.php`  
Helper source: none  
Namespace: `System\Interfaces`

Contract for lightweight application bootstrap classes loaded from `app/Bootable`.

## Implementation Usage

```php
namespace App\Bootable;

use System\Interfaces\IBootable;

class ExecAllRoutes implements IBootable
{
    public static function boot(): void
    {
        // Register shared state, routes, or startup behavior.
    }
}
```

## Helper Usage

There is no procedural helper for this interface. `System\Core\PHPAutoload::boot()` discovers and executes implementations.

## Method Signature

| Method | Accepts | Returns |
| --- | --- | --- |
| `public static function boot(): void` | No arguments. | Nothing. |

## Notes

- Implementations must be under the `App\Bootable` namespace for automatic discovery.
- The class name must match the file path expected by PSR-4 autoloading.
