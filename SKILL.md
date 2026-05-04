# Contexto do PHP Mini MVC

Este arquivo serve como guia de contexto para IAs e desenvolvedores criarem novos projetos sobre este mini-framework sem precisar redescobrir o funcionamento interno a cada tarefa.

## Resumo

O projeto e um mini-framework PHP 8.2 baseado em MVC, com foco em landing pages, sites pequenos, APIs simples e sistemas enxutos. Ele usa Composer com PSR-4 para carregar namespaces `App\` e `System\`, `miladrahimi/phprouter` para rotas, Laminas Diactoros para respostas PSR-7 e `vlucas/phpdotenv` para configuracao via `.env`.

O objetivo principal e manter a estrutura simples, previsivel e com poucas dependencias. Antes de adicionar bibliotecas novas, prefira usar os helpers existentes e classes do core.

## Estrutura Principal

```text
app/
  Bootable/        Classes executadas em todo bootstrap, quando implementam IBootable.
  Controllers/     Controllers da aplicacao, namespace App\Controllers.
  Middlewares/     Middlewares usados em rotas/grupos de rotas.
  Models/          Models da aplicacao, namespace App\Models.
  helpers/         Helpers especificos do app, carregados conforme APP_HELPERS_AUTOLOAD.
  routes/          Arquivos de rotas web.php e api.php.
  views/
    pages/         Views de pagina, incluidas pelo template.
    templates/     Layouts/templates reutilizaveis.

languages/         Arquivos JSON de idioma, recursivos e prefixados pela pasta.
public/            Document root esperado pelo servidor; contem index.php e assets.
storage/
  logs/            Logs diarios gerados pelos handlers de erro.
  sessions/        Sessao por arquivos quando SESSION_DRIVER=files.
system/
  Config/          Resolvedores de configuracao/env.
  Core/            Nucleo do framework.
  helpers/         API procedural curta para o core.
  includes/        Handlers de erro e sessao.
  Interfaces/      Contratos do sistema.
  Session/         Session handlers customizados.
```

## Ciclo De Requisicao

O ponto de entrada e `public/index.php`. O servidor deve apontar o document root para `public/` ou redirecionar todas as requisicoes para esse arquivo.

Fluxo de bootstrap:

1. Carrega `../vendor/autoload.php`.
2. Inclui `system/includes/error_handlers.php`.
3. Executa `Globals::loadEnv()` e le variaveis do `.env`.
4. Detecta se a URL e API com `Globals::isApiRequest()`.
5. Ajusta exibicao de erros conforme `APP_ENV`.
6. Carrega todos os helpers internos de `system/helpers`.
7. Carrega helpers do app conforme `APP_HELPERS_AUTOLOAD`.
8. Configura sessao:
   - web: usa `system/includes/session_handlers.php`;
   - API: desativa cookies e usa `System\Session\NULLHandler`.
9. Conecta banco automaticamente se `DB_DRIVER` for valido.
10. Executa classes bootaveis em `app/Bootable`.
11. Carrega rotas:
   - web: `app/routes/web.php`;
   - API: `app/routes/api.php` dentro do prefixo `/api`.
12. Despacha a rota pelo `RouterLoader`.
13. Se ocorrer `RouteNotFoundException`, retorna HTML 404.
14. Se ocorrer outro erro, retorna HTML 500 e, fora de producao, mostra detalhes e grava log diario.

Controllers e rotas devem retornar objetos `Psr\Http\Message\ResponseInterface`, normalmente criados por `response_html()`, `response_json()`, `response_redirect()`, etc.

## Configuracao `.env`

As variaveis principais ficam em `.env.example`:

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

- `APP_ENV`: aceita `production`, `development` ou `testing`; fallback e `production`.
- `BASE_PATH`: use quando o app roda em subdiretorio. Exemplo: `/php-mini-mvc`.
- `DEFAULT_LANGUAGE`: idioma padrao usado pelo sistema de traducoes.
- `APP_HELPERS_AUTOLOAD`: `true`, `1`, `all` ou `*` carrega todos os helpers de `app/helpers`; tambem aceita lista como `['foo','bar.php']`.
- `SESSION_DRIVER`: implementacao aceita `files`, `db` ou `none`.
- `DB_DRIVER`: implementacao aceita `mysql`, `pgsql` ou `none`.

## Rotas

As rotas usam `miladrahimi/phprouter`. Cada arquivo de rota recebe uma variavel local `$router`.

`app/routes/web.php` e carregado para paginas web:

```php
$router->get('/', function () {
    $html = view_render_page('home');
    return response_html($html);
});

$router->group(['middleware' => [\App\Middlewares\Example::class], 'prefix' => '/admin'], function($router) {
    $router->get('/users/{id}', [\App\Controllers\User::class, 'showPage']);
});
```

`app/routes/api.php` e carregado com prefixo global `/api`. Se o arquivo define grupo `/admin`, o endpoint final sera `/api/admin/...`.

```php
$router->group(['prefix' => '/admin'], function($router) {
    $router->get('/users', [\App\Controllers\User::class, 'index']);
});
```

Quando `BASE_PATH` estiver definido, o `RouterLoader` agrupa as rotas dentro desse prefixo. Use `path_base()` e `site_url()` para gerar URLs compativeis com subdiretorios.

## Middlewares

Middlewares ficam em `app/Middlewares` e normalmente possuem metodo `handle(ServerRequestInterface $request, \Closure $next)`.

Exemplo:

```php
namespace App\Middlewares;

use Psr\Http\Message\ServerRequestInterface;

class Auth
{
    public function handle(ServerRequestInterface $request, \Closure $next)
    {
        if (!session_has('user_id')) {
            return response_redirect('/login');
        }

        return $next($request);
    }
}
```

Evite middlewares globais pesados. O README orienta manter middlewares focados e especificos por rota.

## Controllers E Models

Controllers ficam em `app/Controllers` e usam namespace `App\Controllers`. Models ficam em `app/Models` e usam namespace `App\Models`.

Exemplo atual:

- `App\Controllers\User::index()` retorna JSON de usuarios para API.
- `App\Controllers\User::showPage(int $id)` busca usuario e renderiza `user-profile`.
- `App\Controllers\User::redirectToList()` redireciona para `/api/admin/users`.
- `App\Models\User` e um model de exemplo com dados em memoria e comentarios mostrando como trocar por `Database::select()` e `Database::selectRow()`.

Padrao recomendado:

```php
namespace App\Controllers;

use App\Models\ProductModel;
use Psr\Http\Message\ResponseInterface;

class Products
{
    public function show(int $id): ResponseInterface
    {
        $model = new ProductMode();
        $product = $model->find($id);

        if (!$product) {
            return response_html(view_render_html('<h1>Not found</h1>'), 404);
        }

        return response_html(view_render_page('products/show', [
            'product' => $product,
            'title' => 'Product',
        ]));
    }
}
```

## Views E Templates

O renderizador e `System\Core\View`.

Arquivos:

- Paginas: `app/views/pages/*.php`.
- Templates: `app/views/templates/*.php`.
- Template padrao: `app/views/templates/template.php`.

`View::render_page('home', $data)`:

1. Mescla variaveis globais de view com `$data`.
2. Faz `extract(...)`.
3. Inclui o template atual.
4. O template inclui a pagina em `app/views/pages/$page.php`.

`View::render_html($html, $data)` renderiza um bloco de HTML bruto dentro do template, sem incluir pagina.

Helpers equivalentes:

```php
view_share('title', 'Minha pagina');
view_share_many(['user' => $user]);
view_forget('user');
view_forget_many(['user', 'title']);
view_set_template('template');
view_get_template();
view_render_page('home', ['title' => 'Home']);
view_render_html('<h1>OK</h1>');
view_globals();
```

Ao criar assets em views, prefira `path_base_public()`:

```php
<link rel="stylesheet" href="<?= path_base_public() ?>/assets/css/app.css">
<script src="<?= path_base_public() ?>/assets/js/app.js"></script>
```

## Home Como Documentacao Dinamica

`app/views/pages/home.php` e uma pagina de documentacao do framework. Ela:

- Usa `lg(...)` para buscar textos em `languages/doc/*`.
- Exibe um resumo do framework.
- Define um array `$docs` com classes, metodos, exemplos de codigo, helper alternativo e chave de descricao.
- Renderiza esse array como secoes HTML `<details>`.

Se novas classes publicas forem adicionadas ao framework, a home pode ser atualizada adicionando novas entradas no array `$docs` e novas chaves nos arquivos `languages/doc/en.json` e `languages/doc/pt-br.json`.

## Sistema De Idiomas

Classe principal: `System\Core\Language`.

Helpers:

```php
lg('template.framework.name');
lg('system.database.connection.error.info', ['error' => $message]);
language_get('pages.users.profile');
language_load('pt-br');
ld('en');
language_current();
language_default();
language_detect();
```

Funcionamento:

1. O sistema procura recursivamente arquivos com nome exato do idioma, por exemplo `pt-br.json`.
2. Arquivos na raiz de `languages/` nao recebem prefixo.
3. Arquivos em subpastas recebem prefixo baseado no caminho.
4. Todos os JSON encontrados sao mesclados em um array plano.
5. Placeholders `{nome}` sao substituidos pelo array passado a `lg()`.

Exemplos de prefixo:

```text
languages/pt-br.json                    -> back.home
languages/system/pt-br.json             -> system.http.404.title
languages/template/pt-br.json           -> template.framework.name
languages/pages/users/pt-br.json        -> pages.users.profile
languages/doc/pt-br.json                -> doc.body.details
```

Prioridade de carregamento:

1. Idioma completo solicitado ou detectado, como `pt-br`.
2. Prefixo curto quando existe hifen, como `pt`.
3. `DEFAULT_LANGUAGE`.
4. Sem arquivos encontrados: traducoes vazias e idioma atual `null`.

Se uma chave nao existir, `lg()` retorna `null`. Em views, evite depender de chave inexistente.

## Classes Do Core

### `System\Config\Database`

Resolve o driver de banco a partir de `DB_DRIVER`.

Metodos:

- `env(): string`: retorna `mysql`, `pgsql` ou `none`.
- `is(string $env): bool`.
- `isMysql(): bool`.
- `isPostgres(): bool`.
- `isNone(): bool`.

Helpers: `database_driver()`, `database_is()`, `database_is_mysql()`, `database_is_postgres()`, `database_is_none()`.

### `System\Config\Session`

Resolve o driver de sessao a partir de `SESSION_DRIVER`.

Metodos:

- `env(): string`: retorna `files`, `db` ou `none`.
- `is(string $env): bool`.
- `isFiles(): bool`.
- `isDB(): bool`.
- `isNone(): bool`.

Helpers: `session_driver()`, `session_is()`, `session_is_files()`, `session_is_db()`, `session_is_none()`.

### `System\Config\Environment`

Resolve o ambiente da aplicacao.

Metodos:

- `env(): string`: retorna `production`, `development` ou `testing`.
- `is(string $env): bool`.
- `isProduction(): bool`.
- `isDevelopment(): bool`.
- `isTesting(): bool`.

Helpers: `environment_type()`, `environment_is()`, `environment_is_production()`, `environment_is_development()`, `environment_is_testing()`.

### `System\Config\Globals`

Registry estatico para configuracoes em memoria e variaveis `.env`.

Metodos:

- `get(?string $key = null, mixed $default = null): mixed`.
- `add(string $key, mixed $value): void`.
- `merge(array $config): void`.
- `forget(string $key): void`.
- `forgetMany(array $keys): void`.
- `reset(): void`.
- `loadEnv(): void`.
- `env(?string $key = null): array|string|null`.
- `getApiPrefix(): string`: atualmente retorna `/api`.
- `isApiRequest(): bool`: remove `BASE_PATH` da URI e testa se comeca com `/api`.

Helpers: `globals_get()`, `globals_add()`, `globals_merge()`, `globals_forget()`, `globals_forget_many()`, `globals_reset()`, `globals_load_env()`, `globals_env()`, `globals_get_api_prefix()`, `globals_is_api_request()`.

### `System\Core\PHPAutoload`

Carrega arquivos PHP recursivamente e executa classes bootaveis.

Metodos:

- `from(string $directory): void`: inclui todos os `.php` do diretorio.
- `boot(): void`: procura arquivos em `app/Bootable`, monta o nome da classe `App\Bootable\...`, verifica se implementa `IBootable` e executa `::boot()`.

Use para helpers e bootstraps pequenos. Evite usar para carregar logica pesada em toda request.

### `System\Core\RouterLoader`

Gerencia a instancia compartilhada do `MiladRahimi\PhpRouter\Router`.

Metodos:

- `load(string $file): void`: carrega `app/routes/$file.php`, respeitando `BASE_PATH`.
- `loadWithPrefix(string $prefix, string $file): void`: carrega arquivo dentro de um prefixo adicional, usado para API.
- `dispatch(): void`: despacha a rota atual.

### `System\Core\Database`

Wrapper PDO singleton com suporte a MySQL e PostgreSQL.

Metodos:

- `connect(): PDO`: conecta usando `DB_DRIVER`, `DB_HOST`, `DB_PORT`, `DB_NAME`, `DB_USER`, `DB_PASS`, `DB_CHARSET`.
- `isInTransaction(): bool`: indica se ha transacao ativa.
- `startTransaction(): bool`: inicia transacao; se ja houver uma, cria savepoint.
- `commitTransaction(): bool`: commita a transacao principal ou libera savepoint.
- `rollbackTransaction(): bool`: rollback da transacao principal ou rollback de savepoint.
- `statement(string $sql, array $params = []): bool`: executa INSERT/UPDATE/DELETE ou SQL sem retorno.
- `getLastInsertedID(): string|false`.
- `select(string $sql, array $params = [], ?string $key = null): array`: retorna todas as linhas ou uma coluna de cada linha.
- `selectRow(string $sql, array $params = [], ?string $key = null): mixed`: retorna uma linha, uma coluna ou `null`.
- `disconnect(): void`.

Exemplo:

```php
$users = database_select('SELECT id, name FROM users WHERE active = ?', [1]);
$user = database_select_row('SELECT * FROM users WHERE id = :id', ['id' => $id]);

database_start_transaction();
try {
    database_statement('INSERT INTO users (name) VALUES (?)', [$name]);
    $id = database_get_last_inserted_id();
    database_commit_transaction();
} catch (\Throwable $e) {
    database_rollback_transaction();
    throw $e;
}
```

### `System\Core\Response`

Factory de respostas PSR-7 usando Laminas Diactoros.

Metodos:

- `redirect(string $uri = '', string $method = 'auto', ?int $code = null)`.
- `html(string $html, int $status = 200)`.
- `text(string $text, int $status = 200)`.
- `json(array|string $data, int $status = 200)`.
- `xml(string $xml, int $status = 200)`.
- `file(string $filePath, string $downloadName, string $hashFile, string $contentType = 'application/octet-stream')`.

Helpers: `response_redirect()`, `response_html()`, `response_text()`, `response_json()`, `response_xml()`, `response_file()`.

`response_redirect('/login')` transforma caminhos relativos em URL absoluta usando `Path::siteURL()`.

### `System\Core\Session`

API estatica para sessao.

Metodos:

- `start(): void`.
- `has(string $key): bool`.
- `get(string $key, mixed $default = null): mixed`.
- `set(string $key, mixed $value): void`.
- `setMany(array $items): void`.
- `forget(string $key): void`.
- `clear(): void`.
- `save(): void`.
- `destroy(): void`.
- `regenerate(bool $deleteOldSession = true): void`.

Helpers: `session_start_safe()`, `session_has()`, `session_get()`, `session_set()`, `session_set_many()`, `session_forget()`, `session_clear()`, `session_save()`, `session_destroy_safe()`, `session_regenerate()`.

Sessao nao deve ser usada em rotas API, pois o bootstrap usa `NULLHandler` e `Session::start()` bloqueia API por `Globals::isApiRequest()`.

### `System\Core\View`

Renderizador simples com template e variaveis compartilhadas.

Metodos:

- `share(string $key, mixed $value): void`.
- `shareMany(array $items): void`.
- `forget(string $key): void`.
- `forgetMany(array $keys): void`.
- `clear(): void`.
- `setTemplate(?string $relativePath = null): void`.
- `getTemplate(): string`.
- `render_page(string $page, array $data = []): string`.
- `render_html(string $html, array $data = []): string`.
- `getGlobals(): array`.

### `System\Core\Language`

Carrega e consulta traducoes.

Metodos:

- `get(?string $key = null, ?array $replacements = null, ?string $lang = null): string|array|null`.
- `currentLang(): ?string`.
- `load(?string $lang = null): void`.
- `defaultLang(): ?string`.
- `detect(): ?string`.

### `System\Core\Path`

Resolve caminhos absolutos do projeto e URLs.

Metodos de diretorio:

- `root()`, `app()`, `appBootable()`, `appHelpers()`, `appRoutes()`, `appMiddlewares()`, `appControllers()`, `appModels()`.
- `appViews()`, `appViewsPages()`, `appViewsTemplates()`.
- `system()`, `systemInterfaces()`, `systemHelpers()`, `systemIncludes()`.
- `public()`, `storage()`, `storageSessions()`, `storageLogs()`, `languages()`.

Metodos de URL/base:

- `basePath(): string`: normaliza `BASE_PATH`.
- `basePathPublic(): string`: retorna `basePath() . "/public"`.
- `siteURL(?string $final = null): string`: monta URL absoluta com protocolo, host e base path.

Helpers seguem o mesmo nome em snake case: `path_root()`, `path_app_views_pages()`, `path_base()`, `site_url()`, etc.

### `System\Core\FormValidator`

Validador de formularios em desenvolvimento, com suporte a dot notation e regras customizadas.

Metodos:

- `setForm(array $data): void`.
- `get(string $key, mixed $default = null): mixed`.
- `has(string $key): bool`.
- `validate(array $rules): bool`.
- `errors(): array`.
- `resetErrors(): void`.
- `registerRule(string $name, callable $fn): void`.

Regras nativas:

- `required`
- `email`
- `min:{n}`
- `max:{n}`
- `same:{field}`
- `numeric`
- `integer`
- `date`
- `regex:{pattern}`
- `in:{a,b,c}`

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

### `System\Session\DBHandler`

Implementa `SessionHandlerInterface` armazenando sessoes em banco.

Caracteristicas:

- Cria tabela `sessions` automaticamente se nao existir.
- Suporta MySQL e PostgreSQL.
- Usa UPSERT para gravar sessoes.
- Pode prefixar IDs com `SESSION_PREFIX`.
- Pode criptografar payload com `SESSION_ENCRYPT_KEY` usando AES-256-CBC.
- Armazena IP, user agent e `updated_at`.

### `System\Session\NULLHandler`

Implementa `SessionHandlerInterface` descartando leitura e escrita. Usado quando sessao esta desabilitada ou em requisicoes API.

### `System\Interfaces\IBootable`

Contrato para classes em `app/Bootable`.

```php
namespace System\Interfaces;

interface IBootable
{
    public static function boot(): void;
}
```

Exemplo de bootable:

```php
namespace App\Bootable;

use System\Interfaces\IBootable;
use System\Core\View;

class ShareDefaults implements IBootable
{
    public static function boot(): void
    {
        View::share('appName', 'Minha App');
    }
}
```

Mantenha bootables leves, porque rodam em toda requisicao.

## Helpers Procedurais

Os helpers internos sao carregados automaticamente antes das rotas. Eles sao a API curta recomendada dentro de controllers, views e middlewares.

| Area | Helpers |
| --- | --- |
| Banco config | `database_driver`, `database_is`, `database_is_mysql`, `database_is_postgres`, `database_is_none` |
| Banco core | `database_connect`, `database_select`, `database_select_row`, `database_statement`, `database_get_last_inserted_id`, `database_start_transaction`, `database_commit_transaction`, `database_rollback_transaction`, `database_disconnect` |
| Ambiente | `environment_type`, `environment_is`, `environment_is_production`, `environment_is_development`, `environment_is_testing` |
| Form | `form_validator`, `form_validator_register_rule` |
| Globals | `globals_get`, `globals_add`, `globals_merge`, `globals_forget`, `globals_forget_many`, `globals_reset`, `globals_load_env`, `globals_env`, `globals_get_api_prefix`, `globals_is_api_request` |
| Idiomas | `lg`, `language_get`, `language_load`, `ld`, `language_detect`, `language_current`, `language_default` |
| Path | `path_root`, `path_app`, `path_app_bootable`, `path_app_helpers`, `path_app_routes`, `path_app_middlewares`, `path_app_controllers`, `path_app_models`, `path_app_views`, `path_app_views_pages`, `path_app_views_templates`, `path_system`, `path_system_interfaces`, `path_system_helpers`, `path_system_includes`, `path_public`, `path_storage`, `path_storage_sessions`, `path_storage_logs`, `path_languages`, `path_base`, `path_base_public`, `site_url` |
| Response | `response_redirect`, `response_html`, `response_text`, `response_json`, `response_xml`, `response_file` |
| Sessao | `session_start_safe`, `session_has`, `session_get`, `session_set`, `session_set_many`, `session_forget`, `session_clear`, `session_save`, `session_destroy_safe`, `session_regenerate`, `session_driver`, `session_is`, `session_is_files`, `session_is_db`, `session_is_none` |
| View | `view_share`, `view_share_many`, `view_forget`, `view_forget_many`, `view_set_template`, `view_get_template`, `view_render_page`, `view_render_html`, `view_globals` |

## Criando Uma Nova Pagina

1. Crie a view em `app/views/pages/products/show.php`.
2. Crie as traducoes em `languages/pages/products/pt-br.json` e `languages/pages/products/en.json`.
3. Crie o controller em `app/Controllers/Products.php`.
4. Registre a rota em `app/routes/web.php`.

Exemplo:

```php
// app/routes/web.php
$router->get('/products/{id}', [\App\Controllers\Products::class, 'show']);
```

```php
// app/Controllers/Products.php
namespace App\Controllers;

use App\Models\Product;
use Psr\Http\Message\ResponseInterface;

class Products
{
    public function show(int $id): ResponseInterface
    {
        $product = Product::find($id);

        if (!$product) {
            return response_html(view_render_html('<h1>' . lg('pages.products.not-found') . '</h1>'), 404);
        }

        return response_html(view_render_page('products/show', [
            'title' => lg('pages.products.show.title'),
            'product' => $product,
        ]));
    }
}
```

```php
<!-- app/views/pages/products/show.php -->
<h1><?= htmlspecialchars($product['name']) ?></h1>
<p><?= htmlspecialchars($product['description']) ?></p>
```

```json
{
  "show.title": "Produto",
  "not-found": "Produto nao encontrado"
}
```

Como o arquivo esta em `languages/pages/products/pt-br.json`, as chaves finais sao `pages.products.show.title` e `pages.products.not-found`.

## Criando Uma Rota API

API nao usa sessao e deve retornar JSON, texto, XML, arquivo ou outra resposta PSR-7.

```php
// app/routes/api.php
$router->get('/health', function () {
    return response_json([
        'status' => 'ok',
        'env' => environment_type(),
    ]);
});
```

Com `BASE_PATH=/php-mini-mvc`, a URL final sera `/php-mini-mvc/api/health`.

## Banco De Dados

Para usar banco:

1. Configure `.env` com `DB_DRIVER=mysql` ou `DB_DRIVER=pgsql`.
2. Preencha host, database, user e password.
3. O bootstrap conecta automaticamente se o driver nao for `none`.
4. Use os metodos de `System\Core\Database` ou helpers procedurais.

Exemplo model:

```php
namespace App\Models;

class ProductModel
{
    public function all(): array
    {
        return database_select('SELECT id, name FROM products ORDER BY name');
    }

    public function find(int $id): ?array
    {
        return database_select_row('SELECT * FROM products WHERE id = :id', ['id' => $id]);
    }

    public function create(array $data): bool
    {
        return database_statement(
            'INSERT INTO products (name, description) VALUES (:name, :description)',
            [
                'name' => $data['name'],
                'description' => $data['description'],
            ]
        );
    }
}
```

## Sessao

Para paginas web com sessao:

- `SESSION_DRIVER=files`: salva arquivos em `storage/sessions`.
- `SESSION_DRIVER=db`: usa `System\Session\DBHandler` e exige banco configurado.
- `SESSION_DRIVER=none`: sessao desabilitada.

Exemplo:

```php
session_set('user_id', 123);

if (session_has('user_id')) {
    $id = session_get('user_id');
}

session_regenerate();
session_save();
```

Nao use helpers de sessao em rotas API.

## Logs E Erros

`system/includes/error_handlers.php` registra:

- `set_error_handler` para warnings/notices/errors capturaveis.
- `register_shutdown_function` para erros fatais.

Logs sao gravados em `storage/logs/YYYY-MM-DD.log`.

Em `production`, erros nao sao exibidos. Em `development` e `testing`, erros sao exibidos e tambem logados.

## Convencoes Para IAs

- Respeite PSR-4: classes `App\...` em `app/` e `System\...` em `system/`.
- Controllers devem retornar `ResponseInterface`.
- Use helpers procedurais em views para manter sintaxe curta.
- Use classes do core em logica mais explicita ou quando importar namespace melhorar legibilidade.
- Use `path_base()` para links relativos ao app e assets.
- Use `site_url()` quando precisar de URL absoluta.
- Adicione textos de interface em `languages/*`, nao hardcode textos que precisam de traducao.
- Ao criar arquivo de idioma em subpasta, lembre que a pasta vira prefixo da chave.
- Evite bootables pesados, consultas globais, scans extras e dependencias grandes.
- Em APIs, nao use sessao; projete autenticao API de forma stateless ou por token.
- Para respostas, sempre retorne `response_*()` ou `System\Core\Response::*()`.
- Para banco, prefira prepared statements com parametros.
- Se a aplicacao estiver em subdiretorio, teste URLs com `BASE_PATH`.

## Pontos De Atencao Do Estado Atual

- O README cita driver de sessao `database`, mas o codigo aceita `db`.
- `system/helpers/database.php` define `database_is_transaction_still_ok()`, mas `System\Core\Database` nao possui `isTransactionStillOk()`. Use `Database::isInTransaction()` ou adicione o metodo antes de depender desse helper.
- A documentacao dinamica em `home.php` cita `System\Core\Autoload`, mas a classe real e `System\Core\PHPAutoload`.
- A documentacao dinamica cita `view_clear()`, mas o helper atual `system/helpers/view.php` nao define essa funcao, embora `System\Core\View::clear()` exista.
- `View::render_page()` e `template.php` incluem arquivos de view sem validacao explicita de existencia. Nao passe nome de pagina vindo diretamente do usuario.
- `Language::get()` retorna `null` quando a chave nao existe; ao usar dentro de HTML, garanta que a chave existe.
- `Response::json()` aceita string como JSON bruto; se a string nao for JSON valido, o framework nao valida automaticamente.

## Checklist Para Novos Projetos

1. Copiar `.env.example` para `.env`.
2. Ajustar `APP_ENV`, `BASE_PATH` e `DEFAULT_LANGUAGE`.
3. Escolher `SESSION_DRIVER`.
4. Escolher `DB_DRIVER` ou manter `none`.
5. Criar rotas em `app/routes/web.php` e/ou `app/routes/api.php`.
6. Criar controllers em `app/Controllers`.
7. Criar models em `app/Models` quando houver dados.
8. Criar views em `app/views/pages`.
9. Criar traducoes em `languages/...`.
10. Usar `response_*`, `view_*`, `lg`, `path_base` e `database_*` como API principal.
