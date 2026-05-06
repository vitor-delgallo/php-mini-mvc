# System\Core\PHPAutoload

Source: `system/Core/PHPAutoload.php`  
Helper source: none  
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

There is no procedural helper for this class. Use static methods directly.

## Method Signatures

| Static method | Accepts | Returns |
| --- | --- | --- |
| `PHPAutoload::from(string $directory): void` | Absolute directory path. Recursively includes all `.php` files. | Nothing. Throws `InvalidArgumentException` when the path is not a directory. |
| `PHPAutoload::boot(): void` | No arguments. Scans `app/Bootable` and calls `::boot()` on classes that implement `System\Interfaces\IBootable`. | Nothing. |

## Notes

- `from()` is used during bootstrap to load system helpers and app helpers.
- `boot()` assumes the bootable class namespace matches `App\Bootable\...` and the file path.
