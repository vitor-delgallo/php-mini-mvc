# Database, Session, and Forms

## Database

Main class:

```php
System\Core\Database
```

The framework uses a PDO singleton with MySQL and PostgreSQL support.

To enable the database:

1. Configure `DB_DRIVER=mysql` or `DB_DRIVER=pgsql`.
2. Fill in host, port, database name, user, and password.
3. The bootstrap connects automatically when the driver is not `none`.
4. Use `database_*()` helpers or core methods.

Main helpers:

```php
database_connect();
database_select($sql, $params = [], $key = null);
database_select_row($sql, $params = [], $key = null);
database_statement($sql, $params = []);
database_get_last_inserted_id();
database_is_in_transaction();
database_start_transaction();
database_commit_transaction();
database_rollback_transaction();
database_disconnect();
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
```

Transaction example:

```php
database_start_transaction();

try {
    database_statement(
        'INSERT INTO users (name) VALUES (?)',
        [$name]
    );

    $id = database_get_last_inserted_id();

    database_commit_transaction();
} catch (\Throwable $e) {
    if (database_is_in_transaction()) {
        database_rollback_transaction();
    }

    throw $e;
}
```

Always use SQL parameters. Do not concatenate user input directly into queries.

## Session

Main class:

```php
System\Core\Session
```

Available drivers:

- `files`: stores files in `storage/sessions`;
- `db`: uses `System\Session\DBHandler`;
- `none`: session disabled.

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
