# Database, Session, and Forms

## Database

Main class:

```php
System\Core\Database
```

The framework uses a PDO connection registry with MySQL and PostgreSQL support.

The unsuffixed `DB_*` variables define the default connection. Suffixed variables define named connections:

```dotenv
DB_DRIVER_APP=mysql
DB_HOST_APP=localhost
DB_NAME_APP=app_db
DB_USER_APP=app_user
```

The lowercase suffix becomes the connection key. For example, `_APP` becomes `app`, `_AUTH` becomes `auth`, and `_ROBOT` becomes `robot`.

To enable the database:

1. Configure `DB_DRIVER=mysql` or `DB_DRIVER=pgsql`.
2. Fill in host, database name, and user for the default connection.
3. Optionally add suffixed groups for named connections.
4. The bootstrap connects automatically only to the default connection when its driver is not `none`.
5. Named connections open lazily when selected by helper/core calls.
6. Use `database_*()` helpers or core methods.

Required fields for each default or named connection are `driver`, `host`, `name`, and `user`. Password may be empty. Empty port uses the driver default: `3306` for MySQL and `5432` for PostgreSQL. Empty charset uses `utf8`.

Incomplete suffixed groups are ignored. A complete suffixed group with an unsupported driver throws a translated configuration error. Runtime connections can be registered without env variables through `database_configure()`.

Main helpers:

```php
database_connect($connection = 'default');
database_configure($connection, $config);
database_forget_connection($connection);
database_connection_names();
database_has_connection($connection);
database_select($sql, $params = [], $key = null, $connection = 'default');
database_select_row($sql, $params = [], $key = null, $connection = 'default');
database_statement($sql, $params = [], $connection = 'default');
database_get_last_inserted_id($connection = 'default');
database_is_in_transaction($connection = 'default');
database_start_transaction($connection = 'default');
database_commit_transaction($connection = 'default');
database_rollback_transaction($connection = 'default');
database_disconnect($connection = null);
```

Query example:

```php
$users = database_select(
    'SELECT id, name FROM users WHERE active = ?',
    [1]
);

$user = database_select_row(
    'SELECT * FROM users WHERE id = :id',
    ['id' => $id]
);

$authUsers = database_select(
    'SELECT id, email FROM users WHERE active = ?',
    [1],
    null,
    'auth'
);
```

Runtime connection example:

```php
database_configure('reporting', [
    'driver' => 'mysql',
    'host' => '127.0.0.1',
    'name' => 'reports',
    'user' => 'report_user',
]);

$reports = database_select('SELECT id, title FROM reports', [], null, 'reporting');

database_forget_connection('reporting');
```

Transaction example:

```php
database_start_transaction();

try {
    database_statement(
        'INSERT INTO users (name) VALUES (?)',
        [$name]
    );

    $id = database_get_last_inserted_id('default');

    database_commit_transaction();
} catch (\Throwable $e) {
    if (database_is_in_transaction()) {
        database_rollback_transaction();
    }

    throw $e;
}
```

Always use SQL parameters. Do not concatenate user input directly into queries.

`database_disconnect()` with no argument closes all cached PDO instances. Passing a connection key closes only that cached connection.

## Session

Main class:

```php
System\Core\Session
```

Available drivers:

- `files`: stores files in `storage/sessions`;
- `db`: uses `System\Session\DBHandler`;
- `none`: session disabled.

When `SESSION_DRIVER=db`, the selected database connection comes from `SESSION_DB`. Empty `SESSION_DB` uses `default`; filled values should be lowercase connection names such as `app`, `auth`, or `robot`. The selected connection may come from suffixed env config or from runtime registration, but it must exist before session setup runs.

If DB sessions are enabled and the selected connection is missing, incomplete, unsupported, or `none`, the bootstrap throws a translated internal configuration error.

Helpers:

```php
session_start_safe();
session_has($key);
session_get($key, $default = null);
session_set($key, $value);
session_set_many($items);
session_forget($key);
session_clear();
session_save();
session_destroy_safe();
session_regenerate();
```

Example:

```php
session_set('user_id', 123);

if (session_has('user_id')) {
    $id = session_get('user_id');
}

session_regenerate();
session_save();
```

Important: **do not use sessions in API routes**. The bootstrap uses `NULLHandler` for APIs, and `Session::start()` blocks APIs through `Globals::isApiRequest()`.

## FormValidator

Class:

```php
System\Core\FormValidator
```

Helpers:

```php
form_validator($_POST, reset: true);
form_validator_register_rule($name, $callback);
```

Native rules:

```text
required
email
min:{n}
max:{n}
same:{field}
numeric
integer
date
regex:{pattern}
in:{a,b,c}
```

Example:

```php
$form = form_validator($_POST, reset: true);

if (!$form->validate([
    'email' => 'required|email',
    'password' => 'required|min:8',
])) {
    return response_html(view_render_page('form', [
        'errors' => $form->errors(),
    ]), 422);
}
```

For arrays, use `..` to iterate children:

```php
$form->validate([
    'users..email' => 'required|email',
]);
```
