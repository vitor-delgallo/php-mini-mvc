[Read in English](README.md)

# PHP Mini MVC

PHP Mini MVC é um mini-framework MVC simples e direto para PHP 8.2+. Ele foi pensado para landing pages, sites institucionais, sistemas pequenos, APIs simples e aplicações tradicionais com HTML, CSS e JavaScript.

O projeto prioriza estrutura previsível, helpers procedurais, respostas PSR-7, traduções em JSON, acesso a banco com PDO e um conjunto mínimo de dependências.

## O Que Este Projeto É

- Um esqueleto MVC leve para PHP 8.2+.
- Um núcleo simples de framework em `system/`.
- Uma camada de aplicação convencional em `app/`.
- Uma boa base para sites pequenos e APIs simples.
- Uma codebase feita para continuar fácil de ler, alterar e publicar.

## O Que Este Projeto Não É

- Não é Laravel, Symfony, Slim ou um framework enterprise completo.
- Não assume React, Vue, Angular, jQuery ou pipeline de build front-end.
- Não tenta esconder PHP atrás de abstrações pesadas.

## Requisitos

- PHP 8.2 ou superior
- Composer
- Extensões PHP:
  - `openssl`
  - `pdo`
- Um servidor web capaz de direcionar requisições para `public/index.php`

## Dependências Principais

- `miladrahimi/phprouter` para rotas
- `laminas/laminas-diactoros` para respostas PSR-7
- `laminas/laminas-httphandlerrunner`
- `vlucas/phpdotenv` para variáveis de ambiente
- PDO para acesso a banco de dados

## Instalação

```bash
git clone https://github.com/vitor-delgallo/php-mini-mvc.git
cd php-mini-mvc
composer install
```

Crie o arquivo de ambiente:

```bash
cp .env.example .env
```

No Windows PowerShell:

```powershell
Copy-Item .env.example .env
```

Aponte o document root do servidor web para `public/`, ou use o `.htaccess` incluído na raiz do projeto para direcionar as requisições para `public/index.php` no Apache.

## Ambiente

Os principais valores do `.env` são:

```dotenv
APP_ENV=development
BASE_PATH=/php-mini-mvc
DEFAULT_LANGUAGE=en
APP_HELPERS_AUTOLOAD=true

SESSION_DRIVER=none
SESSION_DB=
SESSION_PREFIX=
SESSION_ENCRYPT_KEY=

DB_DRIVER=none
DB_HOST=
DB_PORT=
DB_NAME=
DB_USER=
DB_PASS=
DB_CHARSET=utf8

DB_DRIVER_APP=
DB_HOST_APP=
DB_PORT_APP=
DB_NAME_APP=
DB_USER_APP=
DB_PASS_APP=
DB_CHARSET_APP=
```

Valores importantes:

- `APP_ENV`: `production`, `development` ou `testing`.
- `BASE_PATH`: use quando a aplicação roda em subdiretório, como `/php-mini-mvc`.
- `DEFAULT_LANGUAGE`: idioma padrão usado pelo sistema de traduções.
- `APP_HELPERS_AUTOLOAD`: `true` para carregar todos os helpers da aplicação, ou uma lista como `['auth','format.php']`.
- `SESSION_DRIVER`: `files`, `db` ou `none`.
- `SESSION_DB`: conexão nomeada opcional para `SESSION_DRIVER=db`; vazio usa a conexão padrão `DB_*`.
- `DB_DRIVER`: `mysql`, `pgsql` ou `none` para a conexão padrão de banco.
- `DB_*_<SUFFIX>`: conexões nomeadas opcionais, como `DB_DRIVER_APP`, `DB_HOST_AUTH` ou `DB_NAME_ROBOT`.

## Estrutura do Projeto

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
public/             Document root esperado
storage/
  ia-context/       Documentos de contexto para agentes de IA e mantenedores
  logs/             Logs diários
  sessions/         Arquivos de sessão quando SESSION_DRIVER=files

system/
  Config/           Resolvedores de configuração e ambiente
  Core/             Classes centrais do framework
  helpers/          Helpers procedurais internos
  includes/         Handlers de erro e sessão
  Interfaces/       Contratos do sistema
  Session/          Session handlers customizados
```

## Ciclo de Requisição

Todas as requisições entram por:

```text
public/index.php
```

O bootstrap:

1. Carrega o autoload do Composer.
2. Carrega os handlers de erro.
3. Carrega o `.env`.
4. Detecta se a requisição é API.
5. Carrega helpers do sistema e helpers da aplicação.
6. Configura sessão para requisições web, ou desativa cookies e usa `NULLHandler` para requisições API.
7. Conecta ao banco padrão quando `DB_DRIVER` não é `none`; conexões nomeadas abrem sob demanda.
8. Executa classes bootable em `app/Bootable`.
9. Carrega rotas web de `app/routes/web.php` ou rotas API de `app/routes/api.php` com prefixo `/api`.
10. Despacha a requisição pelo router.

## Rotas

As rotas usam `miladrahimi/phprouter`. Cada arquivo de rota recebe uma variável local `$router`.

Rotas web ficam em:

```text
app/routes/web.php
```

Exemplo:

```php
use MiladRahimi\PhpRouter\Router;

$router->get('/', function () {
    return response_html(view_render_page('home'));
});

$router->group([
    'middleware' => [\App\Middlewares\Example::class],
    'prefix' => '/admin',
], function (Router $router) {
    $router->get('/users/{id}', [\App\Controllers\User::class, 'showPage']);
});
```

Rotas API ficam em:

```text
app/routes/api.php
```

Elas são carregadas com prefixo global `/api`:

```php
$router->group(['prefix' => '/admin'], function (Router $router) {
    $router->get('/users', [\App\Controllers\User::class, 'index']);
});
```

Com `BASE_PATH=/php-mini-mvc`, a rota API acima fica disponível em:

```text
/php-mini-mvc/api/admin/users
```

## Controllers

Controllers ficam em `app/Controllers` e usam o namespace `App\Controllers`. Eles devem retornar `Psr\Http\Message\ResponseInterface`.

```php
namespace App\Controllers;

use App\Models\User as UserModel;
use Psr\Http\Message\ResponseInterface;

class User
{
    public function index(): ResponseInterface
    {
        return response_json(UserModel::all());
    }

    public function showPage(int $id): ResponseInterface
    {
        $user = UserModel::find($id);

        if (empty($user)) {
            return response_html(view_render_html('<h4>User not found</h4>'), 404);
        }

        return response_html(view_render_page('user-profile', [
            'user' => $user,
        ]));
    }
}
```

Padrão recomendado:

- Mantenha decisões de resposta HTTP nos controllers.
- Mantenha queries e persistência nos models.
- Mantenha HTML nas views.
- Retorne respostas com `response_html()`, `response_json()`, `response_redirect()` e helpers relacionados.

## Views e Templates

Views de página ficam em:

```text
app/views/pages/
```

Templates ficam em:

```text
app/views/templates/
```

Renderizar uma página:

```php
return response_html(view_render_page('home', [
    'title' => 'Home',
]));
```

Renderizar um pequeno bloco de HTML bruto:

```php
return response_html(view_render_html('<h1>OK</h1>'));
```

Compartilhar dados globalmente com views:

```php
view_share('appName', 'PHP Mini MVC');
view_share_many(['theme' => 'light']);
```

## Assets e URLs

Use helpers de caminho em vez de caminhos absolutos fixos.

```php
<link rel="stylesheet" href="<?= path_base_public() ?>/assets/css/app.css">
<script src="<?= path_base_public() ?>/assets/js/app.js"></script>
```

Use `site_url()` para URLs absolutas:

```php
$loginUrl = site_url('/login');
```

Isso mantém o projeto compatível com deploys em subdiretório usando `BASE_PATH`.

## Sistema de Idiomas

Traduções ficam em `languages/` como arquivos JSON. Arquivos em subpastas recebem prefixos baseados no caminho.

Exemplo:

```text
languages/system/en.json      -> system.http.404.title
languages/template/en.json    -> template.framework.name
languages/pages/users/en.json -> pages.users.profile
```

Uso:

```php
echo lg('template.framework.name');

echo lg('system.database.connection.error.info', [
    'error' => $message,
]);
```

Prioridade de carregamento:

1. Idioma completo solicitado ou detectado, como `pt-br`.
2. Prefixo curto do idioma, como `pt`.
3. `DEFAULT_LANGUAGE`.
4. Traduções vazias quando nada é encontrado.

## Banco de Dados

Defina `DB_DRIVER=mysql` ou `DB_DRIVER=pgsql` no `.env` e configure as demais variáveis da conexão padrão. O bootstrap conecta automaticamente na conexão padrão quando o driver não é `none`.

Você pode adicionar conexões nomeadas com sufixos em maiúsculo. O sufixo em minúsculo vira o nome da conexão:

```dotenv
DB_DRIVER_APP=mysql
DB_HOST_APP=localhost
DB_NAME_APP=app_db
DB_USER_APP=app_user
DB_PASS_APP=
DB_PORT_APP=
DB_CHARSET_APP=utf8mb4

DB_DRIVER_AUTH=pgsql
DB_HOST_AUTH=localhost
DB_NAME_AUTH=auth_db
DB_USER_AUTH=auth_user
```

Para cada conexão com sufixo, `DRIVER`, `HOST`, `NAME` e `USER` são obrigatórios. `PASS`, `PORT` e `CHARSET` são opcionais. Grupos ausentes ou incompletos são ignorados. A porta padrão é `3306` para MySQL e `5432` para PostgreSQL; o charset padrão é `utf8`.

Use os helpers de banco:

```php
$users = database_select(
    'SELECT id, name FROM users WHERE active = ?',
    [1]
);

$user = database_select_row(
    'SELECT * FROM users WHERE id = :id',
    ['id' => $id]
);

database_statement(
    'INSERT INTO users (name, email) VALUES (:name, :email)',
    ['name' => $name, 'email' => $email]
);

$authUsers = database_select(
    'SELECT id, email FROM users WHERE active = ?',
    [1],
    null,
    'auth'
);
```

Também é possível registrar conexões em runtime quando elas não devem vir do ambiente:

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

Transações:

```php
database_start_transaction();

try {
    database_statement('UPDATE accounts SET balance = balance - ? WHERE id = ?', [100, $from]);
    database_statement('UPDATE accounts SET balance = balance + ? WHERE id = ?', [100, $to]);

    database_commit_transaction();
} catch (Throwable $e) {
    if (database_is_in_transaction()) {
        database_rollback_transaction();
    }

    throw $e;
}
```

Sempre use parâmetros ou prepared statements. Não concatene entrada de usuário diretamente no SQL.

## Sessões

Configure sessões com:

```dotenv
SESSION_DRIVER=none
SESSION_DB=
```

Drivers suportados:

- `files`: sessões nativas em arquivos dentro de `storage/sessions`.
- `db`: sessões no banco através de `System\Session\DBHandler`.
- `none`: sessão desabilitada.

Quando `SESSION_DRIVER=db`, as sessões usam a conexão padrão `DB_*`, a menos que `SESSION_DB` informe uma conexão configurada, como `app`, `auth` ou `robot`. Se a conexão selecionada estiver ausente, incompleta, sem suporte ou desativada, o framework trata como erro interno de configuração.

Helpers comuns:

```php
session_set('user_id', 123);

if (session_has('user_id')) {
    $id = session_get('user_id');
}

session_regenerate();
session_save();
```

Rotas API não devem usar sessão. Requisições API usam `System\Session\NULLHandler`.

## Validação de Formulários

Use `form_validator()` para dados de request:

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

Para arrays, use `..` para iterar filhos:

```php
$form->validate([
    'users..email' => 'required|email',
]);
```

## Bootables

Classes bootable ficam em `app/Bootable` e implementam `System\Interfaces\IBootable`.

```php
namespace App\Bootable;

use System\Interfaces\IBootable;
use System\Core\View;

class ShareDefaults implements IBootable
{
    public static function boot(): void
    {
        View::share('appName', 'PHP Mini MVC');
    }
}
```

Bootables rodam em toda requisição. Mantenha essas classes leves e evite queries globais ou inicializações caras.

## Contexto Para IA

Este repositório inclui contexto estruturado para agentes de código com IA:

```text
storage/ia-context/mvc.md
storage/ia-context/mvc-references/
```

Use `storage/ia-context/mvc.md` como ponto de entrada. Os arquivos de referência separam arquitetura, configuração, camadas MVC, idiomas, banco/sessão/formulários, respostas/middlewares/bootables, helpers, fluxos e pontos de atenção em documentos focados.

## Princípios de Desenvolvimento

- Mantenha o framework pequeno e previsível.
- Prefira helpers e classes centrais existentes antes de adicionar novas abstrações.
- Evite dependências pesadas sem justificativa explícita.
- Preserve o suporte a `BASE_PATH`.
- Mantenha rotas API stateless.
- Mantenha views simples.
- Mantenha models responsáveis por acesso a dados.
- Mantenha SQL parametrizado.

## Contribuindo

Contribuições são bem-vindas. Você pode abrir issues para bugs, propostas ou melhorias de documentação, e pull requests para mudanças focadas.

Antes de alterar o core do framework, prefira mudanças pequenas, testáveis e que preservem a estrutura existente do projeto.

Repositório: [vitor-delgallo/php-mini-mvc](https://github.com/vitor-delgallo/php-mini-mvc)

## Licença

Este projeto é distribuído sob a [Licença MIT](LICENSE).
