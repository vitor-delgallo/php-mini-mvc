# Configuracao, rotas e URLs

## Variaveis `.env`

```dotenv
APP_ENV=development
BASE_PATH=/php-mini-mvc
DEFAULT_LANGUAGE=en
APP_HELPERS_AUTOLOAD=true

SESSION_DRIVER=none
SESSION_PREFIX=
SESSION_ENCRYPT_KEY=

DB_DRIVER=none
DB_HOST=
DB_PORT=
DB_NAME=
DB_USER=
DB_PASS=
DB_CHARSET=utf8
```

Regras importantes:

- `APP_ENV` aceita `production`, `development` ou `testing`.
- Fallback de ambiente deve ser tratado como `production`.
- `BASE_PATH` deve ser usado quando o app roda em subdiretorio.
- `DEFAULT_LANGUAGE` define o idioma padrao do sistema de traducoes.
- `APP_HELPERS_AUTOLOAD` pode carregar todos os helpers do app ou uma lista especifica.
- `SESSION_DRIVER` aceita `files`, `db` ou `none`.
- `DB_DRIVER` aceita `mysql`, `pgsql` ou `none`.

## Rotas

As rotas usam `miladrahimi/phprouter`.

Cada arquivo de rota recebe uma variavel local:

```php
$router
```

## Rotas web

Arquivo:

```text
app/routes/web.php
```

Exemplo:

```php
$router->get('/', function () {
    $html = view_render_page('home');
    return response_html($html);
});

$router->group([
    'middleware' => [\App\Middlewares\Example::class],
    'prefix' => '/admin',
], function ($router) {
    $router->get('/users/{id}', [\App\Controllers\User::class, 'showPage']);
});
```

## Rotas API

Arquivo:

```text
app/routes/api.php
```

Esse arquivo e carregado com prefixo global `/api`.

Exemplo:

```php
$router->group(['prefix' => '/admin'], function ($router) {
    $router->get('/users', [\App\Controllers\User::class, 'index']);
});
```

Se `BASE_PATH=/php-mini-mvc`, uma rota `/api/health` ficara acessivel em:

```text
/php-mini-mvc/api/health
```

APIs devem retornar JSON, texto, XML, arquivo ou outra resposta PSR-7. Nao use sessao em rotas API.

## `BASE_PATH` e URLs

Quando o projeto roda em subdiretorio, configure `BASE_PATH` no `.env`.

Para gerar URLs e caminhos compativeis, prefira:

```php
path_base();
path_base_public();
site_url();
```

Em views, para assets:

```php
<link rel="stylesheet" href="<?= path_base_public() ?>/assets/css/app.css">
<script src="<?= path_base_public() ?>/assets/js/app.js"></script>
```

Nao escreva caminhos absolutos fixos como `/assets/...` quando o projeto puder rodar em subdiretorio.

## Path helpers principais

Classe principal:

```php
System\Core\Path
```

Helpers comuns:

```php
path_root();
path_app();
path_app_bootable();
path_app_helpers();
path_app_routes();
path_app_middlewares();
path_app_controllers();
path_app_models();
path_app_views();
path_app_views_pages();
path_app_views_templates();
path_system();
path_system_interfaces();
path_system_helpers();
path_system_includes();
path_public();
path_storage();
path_storage_sessions();
path_storage_logs();
path_languages();
path_base();
path_base_public();
site_url();
```

Use `path_base_public()` para assets publicos e `site_url()` para URL absoluta.
