# Add Multiple Database Connections

## Goal

Add first-class support for multiple named database connections configured through `.env` variables with suffixes such as `_APP`, `_AUTH`, and `_ROBOT`.

The existing unsuffixed `DB_*` variables remain the default connection. Existing code that calls `Database::connect()`, `database_select()`, or other database helpers without a connection name must continue to use that default connection.

## Required Context

- Read this plan first.
- Then read `storage/ia-context/mvc.md` for project conventions.
- Do not read `.env`; use only `.env.example` and in-memory test values.
- Also inspect the database config/core/helper/session files, `.env.example`, root READMEs, MVC database/helper docs, `system/views/pages/home.php`, and system language files.

## Environment Contract

Keep the default connection variables unchanged:

```dotenv
DB_DRIVER=none
DB_HOST=
DB_PORT=
DB_NAME=
DB_USER=
DB_PASS=
DB_CHARSET=utf8
```

Add support for suffixed connection groups:

```dotenv
DB_DRIVER_APP=mysql
DB_HOST_APP=localhost
DB_PORT_APP=
DB_NAME_APP=app_db
DB_USER_APP=app_user
DB_PASS_APP=
DB_CHARSET_APP=utf8mb4

DB_DRIVER_AUTH=pgsql
DB_HOST_AUTH=localhost
DB_NAME_AUTH=auth_db
DB_USER_AUTH=auth_user
```

Add optional session database selection to `.env.example` using the current comment style:

```dotenv
# Named database connection used by SESSION_DRIVER=db (leave blank to use the default DB_* connection)
SESSION_DB=
```

Rules:

- A suffix creates a named connection using the lowercase suffix as the key:
  - `_APP` => `app`
  - `_AUTH` => `auth`
  - `_ROBOT` => `robot`
- To create a named connection, these fields must be filled for that suffix:
  - `DB_DRIVER_<SUFFIX>`
  - `DB_HOST_<SUFFIX>`
  - `DB_NAME_<SUFFIX>`
  - `DB_USER_<SUFFIX>`
- If any required suffixed field is missing or empty, ignore that named connection.
- `DB_PASS_<SUFFIX>` may be empty.
- `DB_PORT_<SUFFIX>` may be empty; use default driver ports:
  - MySQL: `3306`
  - PostgreSQL: `5432`
- `DB_CHARSET_<SUFFIX>` may be empty; use the same framework default as the current default connection, currently `utf8`.
- `DB_DRIVER_<SUFFIX>=none` should not create a named connection.
- If a suffixed connection is fully configured but uses an unsupported driver, treat it as a configuration error with translated core messages.

## Default Database And Sessions

PHP sessions using `SESSION_DRIVER=db` use the default unsuffixed `DB_*` connection unless `SESSION_DB` is filled.

`SESSION_DB` is optional and must contain the lowercase connection name, such as `app`, `auth`, or `robot`.

Rules:

- If `SESSION_DB` is empty, DB-backed sessions use `Database::connect('default')`.
- If `SESSION_DB` is filled, DB-backed sessions use `Database::connect(SESSION_DB)`.
- If the selected session connection is missing, incomplete, unsupported, or `none`, treat it as an internal configuration error.
- A session connection selected through `SESSION_DB` may come from suffixed env config or from a runtime-registered connection, but it must exist before session setup runs.

Add translated core messages for all currently available system languages:

- `system/languages/en.json`
- `system/languages/pt-br.json`
- `system/languages/es.json`

Recommended new keys:

- `system.database.default.required-for-session.error`
- `system.database.connection.not-found.info`
- `system.database.connection.incomplete.info`

Reuse existing database error keys where they already fit.

## Implementation Plan

1. Refactor `System\Config\Database` so it can resolve:
   - the default connection config from unsuffixed `DB_*`;
   - all named connection configs from suffixed `DB_*_<SUFFIX>` groups;
   - one connection by name, where `default` means the unsuffixed config;
   - runtime-registered connection configs that do not exist in `.env`.
2. Keep backward-compatible driver helpers:
   - `Database::env()` still returns the default driver.
   - `Database::isMysql()`, `isPostgres()`, and `isNone()` still inspect the default driver unless an optional connection name is added safely.
3. Refactor `System\Core\Database` from one singleton PDO to a connection registry:
   - default key: `default`;
   - named keys: `app`, `auth`, `robot`, etc.;
   - one PDO instance per configured key;
   - one transaction nesting level per connection key.
4. Preserve existing method behavior when no connection name is passed.
5. Add optional connection selection without breaking existing call sites. Recommended signatures:

```php
Database::connect(string $connection = 'default'): PDO;
Database::statement(string $sql, array $params = [], string $connection = 'default'): bool;
Database::select(string $sql, array $params = [], ?string $key = null, string $connection = 'default'): array;
Database::selectRow(string $sql, array $params = [], ?string $key = null, string $connection = 'default'): mixed;
Database::getLastInsertedID(string $connection = 'default'): string|false;
Database::isInTransaction(string $connection = 'default'): bool;
Database::startTransaction(string $connection = 'default'): bool;
Database::commitTransaction(string $connection = 'default'): bool;
Database::rollbackTransaction(string $connection = 'default'): bool;
Database::disconnect(?string $connection = null): void;
```

6. Add discovery helpers if useful:

```php
Database::hasConnection(string $connection): bool;
Database::connectionNames(): array;
```

7. Add a runtime registration API for connections not defined in env. Recommended shape:

```php
Database::configure(string $connection, array $config): void;
Database::forgetConnection(string $connection): void;
```

The config array should accept at least `driver`, `host`, `name`, `user`, `pass`, `port`, and `charset`, with the same required/optional rules as env configs.

8. Update `system/helpers/database.php` to expose the same optional connection and runtime configuration behavior while keeping old helper calls valid.
9. Update `system/includes/session_handlers.php` so DB sessions read `SESSION_DB`, default to `default`, and connect through the selected connection key.
10. Update bootstrap auto-connect behavior carefully:
   - keep the current automatic default connection behavior if a default DB is configured;
   - do not automatically open every named connection at bootstrap;
   - named connections should connect lazily when requested.
   - do not auto-connect runtime-registered connections.

## Documentation Updates

Update `.env.example`, `README.md`, `README.pt-br.md`, `storage/ia-context/mvc.md`, database/helper MVC references, `system/views/pages/home.php`, and `system/languages/doc/{en,pt-br,es}.json`.

The root READMEs must explain:

- default `DB_*` connection behavior;
- suffixed connection naming, such as `_APP`, `_AUTH`, `_ROBOT`;
- required and optional fields;
- examples of selecting a named connection;
- runtime registration for connections not present in env;
- `SESSION_DB` behavior for DB-backed PHP sessions, including the default fallback.

## Tests And Verification

- Run `php -l` on every changed PHP file.
- Validate all changed JSON files.
- Add or run lightweight PHP checks that set environment values in memory without reading `.env`.
- Verify:
  - incomplete suffixed groups are ignored, while complete `_APP`, `_AUTH`, and `_ROBOT` groups create `app`, `auth`, and `robot`;
  - optional password, port, and charset follow the defined fallback rules;
  - unsupported driver in a complete suffixed group produces a translated configuration error;
  - runtime-created connections can be registered, selected, and disconnected;
  - `SESSION_DRIVER=db` with empty `SESSION_DB` and no valid default DB produces the translated internal configuration error;
  - `SESSION_DRIVER=db` with `SESSION_DB=app` uses the `app` connection and errors if that connection is missing;
  - `Database::connect()` remains the default connection;
  - selected named operations use the selected connection key.
- Use Playwright to open `/web-system` after documentation updates and confirm the database documentation renders.

## Acceptance Criteria

- Existing single-database behavior remains backward compatible.
- Multiple named database configs are discovered from suffixed env variables.
- Database connections can also be registered at runtime without env variables.
- Named connections are lazy and do not connect during bootstrap unless explicitly requested.
- DB-backed sessions use the default connection unless `SESSION_DB` names another connection.
- Missing selected DB with `SESSION_DRIVER=db` fails with a translated internal configuration error.
- Core messages exist for all currently available system languages: English, Portuguese, and Spanish.
- `.env.example`, `README.md`, and `README.pt-br.md` document the feature.
- MVC context and home documentation describe the new API and examples.
- The corresponding README item is marked `[CONCLUDED]` only after implementation and verification.
