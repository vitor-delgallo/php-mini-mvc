# System\Config\Database

Source: `system/Config/Database.php`  
Helper source: `system/helpers/database.php`  
Namespace: `System\Config`

Resolves the configured database driver from `DB_DRIVER`. Invalid or missing values fall back to `none`.

## Static Usage

```php
use System\Config\Database;

$driver = Database::env();
if (Database::isMysql()) {
    // MySQL-specific setup
}
```

## Helper Usage

```php
$driver = database_driver();
if (database_is_mysql()) {
    // MySQL-specific setup
}
```

## Method And Helper Signatures

| Static method | Helper | Accepts | Returns |
| --- | --- | --- | --- |
| `Database::env(): string` | `database_driver(): string` | No arguments. Reads `DB_DRIVER` from the loaded env store. | `mysql`, `pgsql`, or `none`. |
| `Database::is(string $env): bool` | `database_is(string $env): bool` | Driver name to compare. Expected values are `mysql`, `pgsql`, or `none`. | `true` when the active driver matches. |
| `Database::isMysql(): bool` | `database_is_mysql(): bool` | No arguments. | `true` when the active driver is `mysql`. |
| `Database::isPostgres(): bool` | `database_is_postgres(): bool` | No arguments. | `true` when the active driver is `pgsql`. |
| `Database::isNone(): bool` | `database_is_none(): bool` | No arguments. | `true` when no valid database driver is configured. |

## Notes

- This class only resolves configuration. Use `System\Core\Database` or `database_*` query helpers for actual connections and SQL.
- `DB_DRIVER=postgres` is not accepted by this class; use `DB_DRIVER=pgsql`.
