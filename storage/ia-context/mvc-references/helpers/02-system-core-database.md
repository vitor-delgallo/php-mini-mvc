# System\Core\Database

Source: `system/Core/Database.php`  
Helper source: `system/helpers/database.php`  
Namespace: `System\Core`

Provides the singleton PDO connection, prepared statement execution, SELECT helpers, last-insert ID lookup, and transaction helpers.

## Static Usage

```php
use System\Core\Database;

$rows = Database::select(
    'SELECT id, name FROM users WHERE active = :active',
    ['active' => 1]
);
```

## Helper Usage

```php
$rows = database_select(
    'SELECT id, name FROM users WHERE active = :active',
    ['active' => 1]
);
```

## Method And Helper Signatures

| Static method | Helper | Accepts | Returns |
| --- | --- | --- | --- |
| `Database::connect(): PDO` | `database_connect(): PDO` | No arguments. Reads `DB_*` env values and opens or returns the cached PDO connection. | The active `PDO` connection. Throws `RuntimeException` when config or connection fails. |
| `Database::select(string $sql, array $params = [], ?string $key = null): array` | `database_select(string $sql, array $params = [], ?string $key = null): array` | Prepared SELECT SQL, bound parameters, and optional column name to extract from each row. | Array of rows, or array of values for `$key`. |
| `Database::selectRow(string $sql, array $params = [], ?string $key = null): mixed` | `database_select_row(string $sql, array $params = [], ?string $key = null): mixed` | Prepared SELECT SQL, bound parameters, and optional column name to extract from the first row. | First row array, scalar value for `$key`, or `null`. |
| `Database::statement(string $sql, array $params = []): bool` | `database_statement(string $sql, array $params = []): bool` | Prepared INSERT, UPDATE, DELETE, DDL, or other SQL plus bound parameters. | `true` on successful execution. |
| `Database::getLastInsertedID(): string|false` | `database_get_last_inserted_id(): string|false` | No arguments. Uses the current PDO connection. | Last insert ID as a string, or `false` if unsupported. |
| `Database::isInTransaction(): bool` | `database_is_in_transaction(): bool` | No arguments. | `true` when the internal transaction level is above zero. |
| `Database::startTransaction(): bool` | `database_start_transaction(): bool` | No arguments. Opens a transaction or creates a savepoint when nested. | `true` or throws on failure. |
| `Database::commitTransaction(): bool` | `database_commit_transaction(): bool` | No arguments. Commits the outer transaction or releases the latest savepoint. | `true` or throws on failure. |
| `Database::rollbackTransaction(): bool` | `database_rollback_transaction(): bool` | No arguments. Rolls back the outer transaction or rolls back to the latest savepoint. | `true` or throws on failure. |
| `Database::disconnect(): void` | `database_disconnect(): void` | No arguments. | Releases the cached PDO connection and resets transaction state. |

## Parameter Rules

- `$sql` must use placeholders for user data. Do not concatenate user input into SQL.
- `$params` should be the associative or positional bind array passed to `PDOStatement::execute()`.
- `$key` is a column name present in the result row. Missing keys return `null` values.

## Notes

- Supported drivers come from `System\Config\Database`: `mysql`, `pgsql`, or `none`.
- Nested transactions are implemented with savepoints named internally as `sp_1`, `sp_2`, and so on.
