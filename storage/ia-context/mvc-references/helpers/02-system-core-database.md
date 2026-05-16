# System\Core\Database

Source: `system/Core/Database.php`  
Helper source: `system/helpers/database.php`  
Namespace: `System\Core`

Provides lazy PDO connections, prepared statement execution, SELECT helpers, last-insert ID lookup, and transaction helpers for the default database and named connections.

## Static Usage

```php
use System\Core\Database;

$rows = Database::select(
    'SELECT id, name FROM users WHERE active = :active',
    ['active' => 1]
);

$authRows = Database::select(
    'SELECT id, email FROM users WHERE active = :active',
    ['active' => 1],
    null,
    'auth'
);
```

## Helper Usage

```php
$rows = database_select(
    'SELECT id, name FROM users WHERE active = :active',
    ['active' => 1]
);

$authRows = database_select(
    'SELECT id, email FROM users WHERE active = :active',
    ['active' => 1],
    null,
    'auth'
);
```

## Method And Helper Signatures

| Static method | Helper | Accepts | Returns |
| --- | --- | --- | --- |
| `Database::connect(string $connection = 'default'): PDO` | `database_connect(string $connection = 'default'): PDO` | Optional connection name. Opens or returns the cached PDO connection for `default` or a named connection. | Selected `PDO` connection. Throws `RuntimeException` when config or connection fails. |
| `Database::configure(string $connection, array $config): void` | `database_configure(string $connection, array $config): void` | Connection name and config array with `driver`, `host`, `name`, `user`, plus optional `pass`, `port`, `charset`. | Registers or replaces a runtime connection config. |
| `Database::forgetConnection(string $connection): void` | `database_forget_connection(string $connection): void` | Connection name. | Removes a runtime connection config and closes any cached PDO for that key. |
| `Database::hasConnection(string $connection): bool` | `database_has_connection(string $connection): bool` | Connection name. | `true` when the connection resolves to a complete config. |
| `Database::connectionNames(): array` | `database_connection_names(): array` | No arguments. | Configured connection names. |
| `Database::select(string $sql, array $params = [], ?string $key = null, string $connection = 'default'): array` | `database_select(string $sql, array $params = [], ?string $key = null, string $connection = 'default'): array` | Prepared SELECT SQL, bound parameters, optional column name, and optional connection. | Array of rows, or array of values for `$key`. |
| `Database::selectRow(string $sql, array $params = [], ?string $key = null, string $connection = 'default'): mixed` | `database_select_row(string $sql, array $params = [], ?string $key = null, string $connection = 'default'): mixed` | Prepared SELECT SQL, bound parameters, optional column name, and optional connection. | First row array, scalar value for `$key`, or `null`. |
| `Database::statement(string $sql, array $params = [], string $connection = 'default'): bool` | `database_statement(string $sql, array $params = [], string $connection = 'default'): bool` | Prepared INSERT, UPDATE, DELETE, DDL, or other SQL plus bound parameters and optional connection. | `true` on successful execution. |
| `Database::getLastInsertedID(string $connection = 'default'): string|false` | `database_get_last_inserted_id(string $connection = 'default'): string|false` | Optional connection name. Uses the selected PDO connection. | Last insert ID as a string, or `false` if unsupported. |
| `Database::isInTransaction(string $connection = 'default'): bool` | `database_is_in_transaction(string $connection = 'default'): bool` | Optional connection name. | `true` when the selected connection transaction level is above zero. |
| `Database::startTransaction(string $connection = 'default'): bool` | `database_start_transaction(string $connection = 'default'): bool` | Optional connection name. Opens a transaction or creates a savepoint when nested. | `true` or throws on failure. |
| `Database::commitTransaction(string $connection = 'default'): bool` | `database_commit_transaction(string $connection = 'default'): bool` | Optional connection name. Commits the outer transaction or releases the latest savepoint. | `true` or throws on failure. |
| `Database::rollbackTransaction(string $connection = 'default'): bool` | `database_rollback_transaction(string $connection = 'default'): bool` | Optional connection name. Rolls back the outer transaction or rolls back to the latest savepoint. | `true` or throws on failure. |
| `Database::disconnect(?string $connection = null): void` | `database_disconnect(?string $connection = null): void` | Optional connection name. `null` closes all cached PDO instances. | Releases cached PDO connection state. |

## Parameter Rules

- `$sql` must use placeholders for user data. Do not concatenate user input into SQL.
- `$params` should be the associative or positional bind array passed to `PDOStatement::execute()`.
- `$key` is a column name present in the result row. Missing keys return `null` values.
- `$connection` defaults to `default`. Pass `app`, `auth`, `robot`, or another configured key to use a named database.
- Runtime configs use the same required fields as env configs: `driver`, `host`, `name`, and `user`.

## Notes

- Supported drivers come from `System\Config\Database`: `mysql`, `pgsql`, or `none`.
- Named env connections are discovered from `DB_*_<SUFFIX>` groups and connect lazily.
- `database_configure()` is for runtime connections not present in env; it does not write to env files.
- Nested transactions are implemented with savepoints named internally as `sp_1`, `sp_2`, and so on.
