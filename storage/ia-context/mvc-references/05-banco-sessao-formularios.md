# Banco, sessao e formularios

## Banco de dados

Classe principal:

```php
System\Core\Database
```

O framework usa PDO singleton com suporte a MySQL e PostgreSQL.

Para ativar banco:

1. Configure `DB_DRIVER=mysql` ou `DB_DRIVER=pgsql`.
2. Preencha host, porta, banco, usuario e senha.
3. O bootstrap conecta automaticamente se o driver nao for `none`.
4. Use helpers `database_*()` ou metodos do core.

Helpers principais:

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

Exemplo de consulta:

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

Exemplo com transacao:

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

Sempre use parametros em SQL. Nao concatene entrada de usuario diretamente em query.

## Sessao

Classe principal:

```php
System\Core\Session
```

Drivers disponiveis:

- `files`: salva arquivos em `storage/sessions`;
- `db`: usa `System\Session\DBHandler`;
- `none`: sessao desabilitada.

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

Exemplo:

```php
session_set('user_id', 123);

if (session_has('user_id')) {
    $id = session_get('user_id');
}

session_regenerate();
session_save();
```

Importante: **nao use sessao em rotas API**. O bootstrap usa `NULLHandler` em APIs e `Session::start()` bloqueia API por `Globals::isApiRequest()`.

## FormValidator

Classe:

```php
System\Core\FormValidator
```

Helpers:

```php
form_validator($_POST, reset: true);
form_validator_register_rule($name, $callback);
```

Regras nativas:

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

Exemplo:

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

Para arrays, use `..` para iterar filhos:

```php
$form->validate([
    'users..email' => 'required|email',
]);
```
