# System\Core\PHPAutoload

Source: `system/Core/PHPAutoload.php`  
Helper source: `system/helpers/php_autoload.php`  
Namespace: `System\Core`

Loads PHP files from directories and boots application bootable classes.

## Static Usage

```php
use System\Core\PHPAutoload;
use System\Core\Path;

PHPAutoload::from(Path::systemHelpers());
PHPAutoload::boot();
```

## Helper Usage

```php
php_autoload_from(path_system_helpers());
php_autoload_boot();
```

## Method And Helper Signatures

| Static method | Helper | Accepts | Returns |
| --- | --- | --- | --- |
| `PHPAutoload::from(string $directory): void` | `php_autoload_from(string $directory): void` | Absolute directory path. Recursively includes all `.php` files. | Nothing. Throws `InvalidArgumentException` when the path is not a directory. |
| `PHPAutoload::boot(): void` | `php_autoload_boot(): void` | No arguments. Scans `app/Bootable` and calls `::boot()` on classes that implement `System\Interfaces\IBootable`. | Nothing. |

## Notes

- `from()` is used during bootstrap to load system helpers and app helpers according to `SYSTEM_HELPERS_AUTOLOAD` and `APP_HELPERS_AUTOLOAD`.
- `boot()` assumes the bootable class namespace matches `App\Bootable\...` and the file path.
- The helpers are thin optional wrappers. Bootstrap and framework runtime code should call `PHPAutoload` directly.
