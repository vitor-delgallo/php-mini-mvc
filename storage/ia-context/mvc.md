# PHP Mini MVC Context

Este documento serve como contexto de projeto para IAs, agentes de código e desenvolvedores que forem trabalhar em cima do **PHP Mini MVC** sem precisar redescobrir a arquitetura interna a cada tarefa.

Use este arquivo como base para `AGENTS.md`, `README_DEV.md` ou como contexto fixo em ferramentas como Codex, Claude Code e outros agentes.

---

## 1. Objetivo do projeto

O projeto é um mini-framework em **PHP 8.2**, baseado em MVC, criado para aplicações enxutas como:

- landing pages;
- sites institucionais;
- sistemas pequenos;
- APIs simples;
- projetos com front-end tradicional em HTML, CSS e JavaScript.

A proposta é manter uma estrutura simples, previsível, com poucas dependências e fácil de adaptar.

Antes de adicionar bibliotecas novas, sempre verifique se já existe helper, classe de core ou padrão interno que resolva o problema.

---

## 2. Stack e dependências principais

O projeto usa:

- **PHP 8.2+**;
- **Composer**;
- **PSR-4** para autoload dos namespaces `App\` e `System\`;
- **miladrahimi/phprouter** para rotas;
- **Laminas Diactoros** para respostas PSR-7;
- **vlucas/phpdotenv** para variáveis de ambiente;
- **PDO** para banco de dados.

Evite converter o projeto para Laravel, Symfony, Slim, React, Vue, Angular ou qualquer outro framework maior sem solicitação explícita.

---

## 3. Estrutura principal

```text
app/
  Bootable/         Classes executadas no bootstrap quando implementam IBootable
  Controllers/      Controllers da aplicação, namespace App\Controllers
  Middlewares/      Middlewares de rotas e grupos de rotas
  Models/           Models da aplicação, namespace App\Models
  helpers/          Helpers específicos do app
  routes/           Arquivos de rota web.php e api.php
  views/
    pages/          Views de página
    templates/      Templates/layouts reutilizáveis

languages/          Arquivos JSON de idioma
public/             Document root esperado pelo servidor
storage/
  logs/             Logs diários
  sessions/         Arquivos de sessão quando SESSION_DRIVER=files

system/
  Config/           Resolvedores de configuração/env
  Core/             Núcleo do framework
  helpers/          Helpers procedurais internos
  includes/         Handlers de erro e sessão
  Interfaces/       Contratos do sistema
  Session/          Session handlers customizados
```

---

## 4. Convenções gerais

Ao trabalhar neste projeto:

- respeite o MVC próprio existente;
- preserve a estrutura `app/`, `system/`, `public/`, `languages/` e `storage/`;
- use os namespaces corretos:
  - `App\...` para código da aplicação;
  - `System\...` para código do framework;
- controllers devem retornar `Psr\Http\Message\ResponseInterface`;
- views devem ser simples e focadas em apresentação;
- models devem concentrar acesso a dados;
- helpers procedurais são a API curta recomendada para views, controllers e middlewares;
- evite introduzir dependências grandes para resolver problemas pequenos;
- não use jQuery, React, Vue ou Angular sem necessidade explícita;
- prefira Bootstrap 5 e JavaScript vanilla para front-end tradicional;
- use prepared statements sempre que houver SQL.

---

## 5. Ciclo de requisição

O ponto de entrada da aplicação é:

```text
public/index.php
```

O servidor deve apontar o document root para `public/` ou redirecionar todas as requisições para esse arquivo.

Fluxo geral do bootstrap:

1. Carrega `../vendor/autoload.php`.
2. Inclui `system/includes/error_handlers.php`.
3. Executa `Globals::loadEnv()`.
4. Lê variáveis do `.env`.
5. Detecta se a requisição é API com `Globals::isApiRequest()`.
6. Ajusta exibição de erros conforme `APP_ENV`.
7. Carrega helpers internos de `system/helpers`.
8. Carrega helpers do app conforme `APP_HELPERS_AUTOLOAD`.
9. Configura sessão:
   - web: usa `system/includes/session_handlers.php`;
   - API: desativa cookies e usa `System\Session\NULLHandler`.
10. Conecta banco automaticamente se `DB_DRIVER` for válido.
11. Executa classes bootáveis em `app/Bootable`.
12. Carrega rotas:

- web: `app/routes/web.php`;
- API: `app/routes/api.php` com prefixo `/api`.

13. Despacha a rota pelo `RouterLoader`.
14. Em caso de `RouteNotFoundException`, retorna HTML 404.
15. Em outros erros, retorna HTML 500; fora de produção, exibe detalhes e grava log diário.

---

## 6. Configuração `.env`

Variáveis principais:

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
- `BASE_PATH` deve ser usado quando o app roda em subdiretório.
- `DEFAULT_LANGUAGE` define o idioma padrão do sistema de traduções.
- `APP_HELPERS_AUTOLOAD` pode carregar todos os helpers do app ou uma lista específica.
- `SESSION_DRIVER` aceita `files`, `db` ou `none`.
- `DB_DRIVER` aceita `mysql`, `pgsql` ou `none`.

---

## 7. Rotas

As rotas usam `miladrahimi/phprouter`.

Cada arquivo de rota recebe uma variável local:

```php
$router
```

### 7.1 Rotas web

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

### 7.2 Rotas API

Arquivo:

```text
app/routes/api.php
```

Esse arquivo é carregado com prefixo global `/api`.

Exemplo:

```php
$router->group(['prefix' => '/admin'], function ($router) {
    $router->get('/users', [\App\Controllers\User::class, 'index']);
});
```

Se `BASE_PATH=/php-mini-mvc`, uma rota `/api/health` ficará acessível em:

```text
/php-mini-mvc/api/health
```

---

## 8. BASE\_PATH e URLs

Quando o projeto roda em subdiretório, use `BASE_PATH` no `.env`.

Para gerar URLs e caminhos compatíveis, prefira os helpers:

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

Não escreva caminhos absolutos fixos como `/assets/...` quando o projeto puder rodar em subdiretório.

---

## 9. Controllers

Controllers ficam em:

```text
app/Controllers
```

Namespace:

```php
namespace App\Controllers;
```

Controllers devem retornar uma resposta PSR-7, normalmente usando helpers `response_*()`.

Exemplo recomendado:

```php
namespace App\Controllers;

use App\Models\ProductModel;
use Psr\Http\Message\ResponseInterface;

class Products
{
    public function show(int $id): ResponseInterface
    {
        $model = new ProductModel();
        $product = $model->find($id);

        if (!$product) {
            return response_html(
                view_render_html('<h1>Not found</h1>'),
                404
            );
        }

        return response_html(view_render_page('products/show', [
            'product' => $product,
            'title' => 'Product',
        ]));
    }
}
```

Padrões importantes:

- não imprimir HTML diretamente no controller;
- não usar `echo` como resposta final;
- retornar `response_html()`, `response_json()`, `response_redirect()` etc.;
- delegar consultas de dados para models;
- delegar HTML para views.

---

## 10. Models

Models ficam em:

```text
app/Models
```

Namespace:

```php
namespace App\Models;
```

Models devem concentrar acesso a dados e regras simples de persistência.

Exemplo:

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
        return database_select_row(
            'SELECT * FROM products WHERE id = :id',
            ['id' => $id]
        );
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

Use sempre parâmetros em queries SQL. Não concatene dados de usuário diretamente no SQL.

---

## 11. Views e templates

O renderizador principal é:

```php
System\Core\View
```

Estrutura:

```text
app/views/pages/       Views de páginas
app/views/templates/   Templates/layouts
```

Template padrão:

```text
app/views/templates/template.php
```

### 11.1 Renderização de página

```php
view_render_page('home', ['title' => 'Home']);
```

O método equivalente no core é:

```php
View::render_page('home', $data);
```

Fluxo:

1. Mescla variáveis globais da view com `$data`.
2. Executa `extract(...)`.
3. Inclui o template atual.
4. O template inclui a página em `app/views/pages/$page.php`.

### 11.2 Renderização de HTML bruto

```php
view_render_html('<h1>OK</h1>');
```

Use para casos simples, páginas de erro ou blocos HTML pequenos.

### 11.3 Helpers de view

```php
view_share('title', 'Minha página');
view_share_many(['user' => $user]);
view_forget('user');
view_forget_many(['user', 'title']);
view_set_template('template');
view_get_template();
view_render_page('home', ['title' => 'Home']);
view_render_html('<h1>OK</h1>');
view_globals();
```

---

## 12. Sistema de idiomas

Classe principal:

```php
System\Core\Language
```

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

1. Procura recursivamente arquivos com nome exato do idioma, por exemplo `pt-br.json`.
2. Arquivos na raiz de `languages/` não recebem prefixo.
3. Arquivos em subpastas recebem prefixo baseado no caminho.
4. Todos os JSON encontrados são mesclados em um array plano.
5. Placeholders como `{name}` são substituídos pelo array passado a `lg()`.

Exemplos de prefixo:

```text
languages/pt-br.json                    -> back.home
languages/system/pt-br.json             -> system.http.404.title
languages/template/pt-br.json           -> template.framework.name
languages/pages/users/pt-br.json        -> pages.users.profile
languages/doc/pt-br.json                -> doc.body.details
```

Prioridade de carregamento:

1. idioma completo solicitado/detectado, como `pt-br`;
2. prefixo curto, como `pt`;
3. `DEFAULT_LANGUAGE`;
4. se nada for encontrado, traduções vazias e idioma atual `null`.

Se a chave não existir, `lg()` retorna `null`. Em HTML, garanta que a chave existe ou trate fallback.

---

## 13. Middlewares

Middlewares ficam em:

```text
app/Middlewares
```

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

Recomendações:

- mantenha middlewares pequenos;
- evite middlewares globais pesados;
- use middlewares por rota ou grupo de rota;
- não use sessão em rotas API.

---

## 14. Banco de dados

Classe principal:

```php
System\Core\Database
```

O framework usa PDO singleton com suporte a MySQL e PostgreSQL.

Para ativar banco:

1. Configure `DB_DRIVER=mysql` ou `DB_DRIVER=pgsql`.
2. Preencha host, porta, banco, usuário e senha.
3. O bootstrap conecta automaticamente se o driver não for `none`.
4. Use helpers `database_*()` ou métodos do core.

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

Exemplo:

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

Exemplo com transação:

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
    if(database_is_in_transaction()) {
      database_rollback_transaction();
    }

    throw $e;
}
```

---

## 15. Respostas HTTP

Classe principal:

```php
System\Core\Response
```

Helpers:

```php
response_redirect($uri = '', $method = 'auto', $code = null);
response_html($html, $status = 200);
response_text($text, $status = 200);
response_json($data, $status = 200);
response_xml($xml, $status = 200);
response_file($filePath, $downloadName, $hashFile, $contentType = 'application/octet-stream');
```

Exemplos:

```php
return response_html(view_render_page('home'));

return response_json([
    'status' => 'ok',
]);

return response_redirect('/login');
```

`response_redirect('/login')` transforma caminhos relativos em URL absoluta usando `Path::siteURL()`.

---

## 16. Sessão

Classe principal:

```php
System\Core\Session
```

Drivers disponíveis:

- `files`: salva arquivos em `storage/sessions`;
- `db`: usa `System\Session\DBHandler`;
- `none`: sessão desabilitada.

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

Importante: **não use sessão em rotas API**. O bootstrap usa `NULLHandler` em APIs e `Session::start()` bloqueia API por `Globals::isApiRequest()`.

---

## 17. FormValidator

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

---

## 18. Path helpers

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

Use `path_base_public()` para assets públicos e `site_url()` para URL absoluta.

---

## 19. Bootables

Classes bootáveis ficam em:

```text
app/Bootable
```

Devem implementar:

```php
System\Interfaces\IBootable
```

Contrato:

```php
namespace System\Interfaces;

interface IBootable
{
    public static function boot(): void;
}
```

Exemplo:

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

Regras:

- bootables rodam em toda requisição;
- mantenha bootables leves;
- evite consultas globais;
- evite lógica pesada;
- use para configurações pequenas, compartilhamento de variáveis e inicializações simples.

---

## 20. Helpers procedurais

Os helpers internos são carregados automaticamente antes das rotas. Eles são a API curta recomendada para a aplicação.

| Área                                                                                  | Helpers                                                                                                                                                                                                                                                                                                                                                                                                                                                          |
| ------------------------------------------------------------------------------------- | ---------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------- |
| Banco config                                                                          | `database_driver`, `database_is`, `database_is_mysql`, `database_is_postgres`, `database_is_none`                                                                                                                                                                                                                                                                                                                                                                |
| Banco core                                                                            | `database_connect`, `database_select`, `database_select_row`, `database_statement`, `database_is_in_transaction`, `database_start_transaction`, `database_commit_transaction`, `database_rollback_transaction`, `database_disconnect`                                                                                                                                                                                                                                                     |
| Ambiente                                                                              | `environment_type`, `environment_is`, `environment_is_production`, `environment_is_development`, `environment_is_testing`                                                                                                                                                                                                                                                                                                                                        |
| Form                                                                                  | `form_validator`, `form_validator_register_rule`                                                                                                                                                                                                                                                                                                                                                                                                                 |
| Globals                                                                               | `globals_get`, `globals_add`, `globals_merge`, `globals_forget`, `globals_forget_many`, `globals_reset`, `globals_load_env`, `globals_env`, `globals_get_api_prefix`, `globals_is_api_request`                                                                                                                                                                                                                                                                   |
| Idiomas                                                                               | `lg`, `language_get`, `language_load`, `ld`, `language_detect`, `language_current`, `language_default`                                                                                                                                                                                                                                                                                                                                                           |
| Path                                                                                  | `path_root`, `path_app`, `path_app_bootable`, `path_app_helpers`, `path_app_routes`, `path_app_middlewares`, `path_app_controllers`, `path_app_models`, `path_app_views`, `path_app_views_pages`, `path_app_views_templates`, `path_system`, `path_system_interfaces`, `path_system_helpers`, `path_system_includes`, `path_public`, `path_storage`, `path_storage_sessions`, `path_storage_logs`, `path_languages`, `path_base`, `path_base_public`, `site_url` |
| Response                                                                              | `response_redirect`, `response_html`, `response_text`, `response_json`, `response_xml`, `response_file`                                                                                                                                                                                                                                                                                                                                                          |
| Sessão                                                                                | `session_start_safe`, `session_has`, `session_get`, `session_set`, `session_set_many`, `session_forget`, `session_clear`, `session_save`, `session_destroy_safe`, `session_regenerate`, `session_driver`, `session_is`, `session_is_files`, `session_is_db`, `session_is_none`                                                                                                                                                                                   |
| View                                                                                  | `view_share`, `view_share_many`, `view_forget`, `view_forget_many`, `view_set_template`, `view_get_template`, `view_render_page`, `view_render_html`, `view_globals`                                                                                                                                                                                                                                                                                             |

---

## 21. Criando uma nova página

Fluxo recomendado:

1. Criar a view em:

```text
app/views/pages/products/show.php
```

2. Criar as traduções em:

```text
languages/pages/products/pt-br.json
languages/pages/products/en.json
```

3. Criar o controller em:

```text
app/Controllers/Products.php
```

4. Registrar a rota em:

```text
app/routes/web.php
```

Exemplo de rota:

```php
$router->get('/products/{id}', [\App\Controllers\Products::class, 'show']);
```

Como o arquivo de idioma está em `languages/pages/products/pt-br.json`, as chaves finais podem ser:

```text
pages.products.show.title
pages.products.not-found
```

---

## 22. Criando uma rota API

APIs não usam sessão e devem retornar JSON, texto, XML, arquivo ou outra resposta PSR-7.

Exemplo:

```php
// app/routes/api.php
$router->get('/health', function () {
    return response_json([
        'status' => 'ok',
        'env' => environment_type(),
    ]);
});
```

Com `BASE_PATH=/php-mini-mvc`, a URL final será:

```text
/php-mini-mvc/api/health
```

---

## 23. Logs e erros

Arquivo:

```text
system/includes/error_handlers.php
```

Ele registra:

- `set_error_handler` para warnings, notices e erros capturáveis;
- `register_shutdown_function` para erros fatais.

Logs são gravados em:

```text
storage/logs/YYYY-MM-DD.log
```

Regras:

- em `production`, erros não devem ser exibidos;
- em `development` e `testing`, erros podem ser exibidos e logados;
- não exponha detalhes sensíveis em produção.

---

## 24. Pontos de atenção do estado atual

- `View::render_page()` e `template.php` incluem arquivos de view sem validação explícita de existência.
- `Language::get()` retorna `null` quando a chave não existe.
- `Response::json()` aceita string como JSON bruto, mas não valida automaticamente se a string é JSON válido.
- Sessão não deve ser usada em rotas API.
- Bootables rodam em toda requisição; não coloque lógica pesada neles.
- Se a aplicação estiver em subdiretório, teste todos os assets e links com `BASE_PATH`.

---

## 25. Regras para agentes de IA

Ao receber uma tarefa neste projeto, siga esta ordem:

1. Leia este contexto.
2. Identifique quais arquivos realmente precisam ser alterados.
3. Antes de criar nova função, procure helper ou classe existente.
4. Antes de criar nova dependência, tente resolver core existente ou com PHP puro.
5. Preserve o MVC próprio.
6. Preserve a compatibilidade com `BASE_PATH`.
7. Preserve o sistema de idiomas quando houver textos de interface.
8. Não reestruture o projeto inteiro para uma tarefa pequena.
9. Entregue alterações pequenas, testáveis e consistentes.

Regras específicas:

- Controllers devem retornar `ResponseInterface`.
- Views podem usar helpers procedurais.
- Models devem concentrar queries, regras de negócio e acesso a dados.
- APIs devem ser stateless ou usar token, não sessão.
- SQL deve usar parâmetros/prepared statements.
- Assets devem usar `path_base_public()`.
- URLs absolutas devem usar `site_url()`.
- Textos traduzíveis devem ir para `languages/*`.
- Arquivos de idioma em subpasta recebem prefixo pelo caminho.
- Não use nome de view vindo diretamente do usuário.

---

## 26. Checklist para novos projetos

1. Copiar `.env.example` para `.env`.
2. Ajustar `APP_ENV`.
3. Ajustar `BASE_PATH`.
4. Ajustar `DEFAULT_LANGUAGE`.
5. Escolher `SESSION_DRIVER`.
6. Escolher `DB_DRIVER` ou manter `none`.
7. Criar rotas em `app/routes/web.php` e/ou `app/routes/api.php`.
8. Criar controllers em `app/Controllers`.
9. Criar models em `app/Models`, quando houver dados.
10. Criar views em `app/views/pages`.
11. Criar traduções em `languages/...`.
12. Usar `response_*`, `view_*`, `lg`, `path_base_*` e `database_*` como API principal.
13. Testar com e sem `BASE_PATH`, quando o projeto puder rodar em subdiretório.
14. Validar comportamento em `development` e `production`.

---

## 27. Resumo para decisões rápidas

| Necessidade                            | Use                                                |
| -------------------------------------- | -------------------------------------------------- |
| Renderizar página                      | `view_render_page()` + `response_html()`           |
| Retornar JSON                          | `response_json()`                                  |
| Redirecionar                           | `response_redirect()`                              |
| Gerar URL absoluta                     | `site_url()`                                       |
| Gerar caminho de asset                 | `path_base_public()`                               |
| Buscar tradução                        | `lg()`                                             |
| Consultar várias linhas                | `database_select()`                                |
| Consultar uma linha                    | `database_select_row()`                            |
| Executar insert/update/delete          | `database_statement()`                             |
| Validar formulário                     | `form_validator()`                                 |
| Ler sessão web                         | `session_get()` / `session_has()`                  |
| Compartilhar variável global com views | `view_share()`                                     |
| Inicialização leve global              | Classe em `app/Bootable` implementando `IBootable` |

---

## 28. Home como documentação dinâmica

A view:

```text
app/views/pages/home.php
```

funciona como documentação dinâmica do framework.

Ela:

- usa `lg(...)` para buscar textos em `languages/doc/*`;
- exibe um resumo do framework;
- define um array `$docs` com classes, métodos, exemplos e descrições;
- renderiza essas entradas como seções HTML `<details>`.

Ao adicionar novas classes públicas ao framework, atualize:

```text
app/views/pages/home.php
languages/doc/en.json
languages/doc/pt-br.json
```

---

## 29. Princípio principal

Este projeto deve continuar sendo um **mini-framework simples, previsível e direto**.

Ao implementar qualquer mudança, prefira a menor alteração correta, mantendo o padrão existente e evitando abstrações desnecessárias.
