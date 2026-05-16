# System\Config\Database

Source: `system/Config/Database.php`  
Helper source: `system/helpers/database.php`  
Namespace: `System\Config`

Resolves database configuration from the loaded env store and runtime registry.

The default connection uses unsuffixed `DB_*` variables. Named connections use suffixed variables like `DB_DRIVER_APP`, `DB_HOST_APP`, `DB_NAME_APP`, and `DB_USER_APP`; the lowercase suffix becomes the connection key, such as `app`.

## Static Usage

```php
use System\Config\Database;

$driver = Database::env();
if (Database::isMysql()) {
    // MySQL-specific setup
}

$authDriver = Database::env('auth');
$connections = Database::connectionNames();
```

## Helper Usage

```php
$driver = database_driver();
if (database_is_mysql()) {
    // MySQL-specific setup
}

$authDriver = database_driver('auth');
$connections = database_connection_names();
$config = database_config_connection('auth');
```

## Method And Helper Signatures

| Static method | Helper | Accepts | Returns |
| --- | --- | --- | --- |
| `Database::env(string $connection = 'default'): string` | `database_driver(string $connection = 'default'): string` | Optional connection name. Reads the resolved driver for `default` or a named connection. | `mysql`, `pgsql`, or `none`. |
| `Database::is(string $env, string $connection = 'default'): bool` | `database_is(string $env, string $connection = 'default'): bool` | Driver name to compare and optional connection name. Expected values are `mysql`, `pgsql`, or `none`. | `true` when the selected connection driver matches. |
| `Database::isMysql(string $connection = 'default'): bool` | `database_is_mysql(string $connection = 'default'): bool` | Optional connection name. | `true` when the selected connection driver is `mysql`. |
| `Database::isPostgres(string $connection = 'default'): bool` | `database_is_postgres(string $connection = 'default'): bool` | Optional connection name. | `true` when the selected connection driver is `pgsql`. |
| `Database::isNone(string $connection = 'default'): bool` | `database_is_none(string $connection = 'default'): bool` | Optional connection name. | `true` when no valid database driver is configured for the selected connection. |
| `Database::connectionNames(): array` | `database_connection_names(): array` | No arguments. | Configured connection names from `default`, suffixed env groups, and runtime registrations. |
| `Database::hasConnection(string $connection): bool` | `database_has_connection(string $connection): bool` | Connection name. | `true` when the connection resolves to a complete config. |
| `Database::permittedDrivers(): array` | `database_config_permitted_drivers(): array` | No arguments. Internal configuration API. | Supported driver names. |
| `Database::normalizeConnectionName(?string $connection = null): string` | `database_config_normalize_connection_name(?string $connection = null): string` | Optional connection name. Internal normalization API. | Lowercase connection key, or `default` for empty values. |
| `Database::defaultPort(string $driver): ?int` | `database_config_default_port(string $driver): ?int` | Driver name. Internal configuration API. | `3306`, `5432`, or `null`. |
| `Database::connection(string $connection = 'default'): ?array` | `database_config_connection(string $connection = 'default'): ?array` | Optional connection name. Internal configuration API. | Normalized config array or `null`. |
| `Database::connections(): array` | `database_config_connections(): array` | No arguments. Internal configuration API. | All resolved connection config arrays. |
| `Database::configure(string $connection, array $config): void` | `database_config_configure(string $connection, array $config): void` | Runtime connection name and config. Low-level config-only wrapper. | Registers or replaces runtime config without touching PDO cache. |
| `Database::forgetConnection(string $connection): void` | `database_config_forget_connection(string $connection): void` | Runtime connection name. Low-level config-only wrapper. | Removes runtime config without touching PDO cache. |

## Notes

- This class only resolves configuration. Use `System\Core\Database` or `database_*` query helpers for actual connections and SQL.
- `DB_DRIVER=postgres` is not accepted by this class; use `DB_DRIVER=pgsql`.
- Required fields for a concrete connection are `driver`, `host`, `name`, and `user`.
- `pass`, `port`, and `charset` are optional. Empty port uses the driver default, and empty charset uses `utf8`.
- Incomplete suffixed env groups are ignored. Complete suffixed groups with unsupported drivers throw translated configuration errors.
- For application code that registers runtime database connections, prefer `database_configure()` / `database_forget_connection()` from `System\Core\Database`; those wrappers also reset cached PDO state. The `database_config_*` helpers are low-level config wrappers.
