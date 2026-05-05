---
name: php-mini-mvc-context
description: Contexto essencial para agentes de IA trabalharem no PHP Mini MVC sem redescobrir a arquitetura interna. Use ao alterar rotas, controllers, models, views, helpers, banco, sessao, traducoes, assets ou bootstrap deste mini-framework PHP 8.2.
---

# PHP Mini MVC Context

Este arquivo e o ponto de entrada do contexto do **PHP Mini MVC** para IAs, agentes de codigo e desenvolvedores.

Mantenha este documento curto o suficiente para ficar sempre no contexto. Abra os documentos em `/storage/ia-context/mvc-references/` somente quando a tarefa exigir detalhes do assunto.

## Essencial que a IA deve saber sempre

- O projeto e um mini-framework em **PHP 8.2+**, baseado em MVC, para landing pages, sites institucionais, sistemas pequenos, APIs simples e front-end tradicional.
- Preserve a estrutura `app/`, `system/`, `public/`, `languages/` e `storage/`.
- Use namespaces `App\...` para codigo da aplicacao e `System\...` para codigo do framework.
- Controllers devem retornar `Psr\Http\Message\ResponseInterface`, normalmente com helpers `response_*()`.
- Views devem ficar focadas em apresentacao; nao coloque regra de negocio nelas.
- Models devem concentrar acesso a dados, queries e regras simples de persistencia.
- Helpers procedurais sao a API curta recomendada para views, controllers e middlewares.
- Antes de criar funcao, classe ou dependencia nova, procure helper, classe de core ou padrao interno existente.
- Evite converter o projeto para Laravel, Symfony, Slim, React, Vue, Angular ou outro framework maior sem pedido explicito.
- Prefira Bootstrap 5 e JavaScript vanilla para front-end tradicional.
- Use prepared statements sempre que houver SQL; nunca concatene entrada de usuario diretamente em query.
- Preserve compatibilidade com `BASE_PATH`; assets devem usar `path_base_public()` e URLs absolutas devem usar `site_url()`.
- Textos traduziveis devem ir para `languages/*` e ser consumidos com `lg()`.
- APIs nao devem usar sessao; o bootstrap usa `NULLHandler` em requisicoes API.
- Entregue alteracoes pequenas, testaveis e consistentes com o MVC proprio do projeto.

## Stack e dependencias

- **PHP 8.2+**
- **Composer**
- **PSR-4** para autoload dos namespaces `App\` e `System\`
- **miladrahimi/phprouter** para rotas
- **Laminas Diactoros** para respostas PSR-7
- **vlucas/phpdotenv** para variaveis de ambiente
- **PDO** para banco de dados

## Estrutura principal

```text
app/
  Bootable/         Classes executadas no bootstrap quando implementam IBootable
  Controllers/      Controllers da aplicacao, namespace App\Controllers
  Middlewares/      Middlewares de rotas e grupos de rotas
  Models/           Models da aplicacao, namespace App\Models
  helpers/          Helpers especificos do app
  routes/           Arquivos de rota web.php e api.php
  views/
    pages/          Views de pagina
    templates/      Templates/layouts reutilizaveis

languages/          Arquivos JSON de idioma
public/             Document root esperado pelo servidor
storage/
  ia-context/       Contextos gerais para a IA
  logs/             Logs diarios
  sessions/         Arquivos de sessao quando SESSION_DRIVER=files

system/
  Config/           Resolvedores de configuracao/env
  Core/             Nucleo do framework
  helpers/          Helpers procedurais internos
  includes/         Handlers de erro e sessao
  Interfaces/       Contratos do sistema
  Session/          Session handlers customizados
```

## Bootstrap em resumo

Entrada da aplicacao:

```text
public/index.php
```

Fluxo essencial:

1. Carrega `../vendor/autoload.php`.
2. Inclui `system/includes/error_handlers.php`.
3. Executa `Globals::loadEnv()` e le `.env`.
4. Detecta API com `Globals::isApiRequest()`.
5. Ajusta exibicao de erros conforme `APP_ENV`.
6. Carrega helpers internos e helpers do app conforme `APP_HELPERS_AUTOLOAD`.
7. Configura sessao: web usa handlers normais; API desativa cookies e usa `System\Session\NULLHandler`.
8. Conecta banco automaticamente se `DB_DRIVER` for valido.
9. Executa bootables em `app/Bootable`.
10. Carrega `app/routes/web.php` ou `app/routes/api.php` com prefixo `/api`.
11. Despacha a rota pelo `RouterLoader`.
12. Retorna HTML 404 para rota nao encontrada e HTML 500 para erros gerais; fora de producao, exibe detalhes e grava log diario.

## Variaveis `.env` principais

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

Regras rapidas:

- `APP_ENV`: `production`, `development` ou `testing`; fallback deve ser tratado como `production`.
- `BASE_PATH`: obrigatorio quando o app roda em subdiretorio.
- `DEFAULT_LANGUAGE`: idioma padrao das traducoes.
- `APP_HELPERS_AUTOLOAD`: `true` para todos os helpers do app ou lista especifica.
- `SESSION_DRIVER`: `files`, `db` ou `none`.
- `DB_DRIVER`: `mysql`, `pgsql` ou `none`.

## Funcoes por helper

| Area | Helpers |
| --- | --- |
| Banco config | `database_driver`, `database_is`, `database_is_mysql`, `database_is_postgres`, `database_is_none` |
| Banco core | `database_connect`, `database_select`, `database_select_row`, `database_statement`, `database_get_last_inserted_id`, `database_is_in_transaction`, `database_start_transaction`, `database_commit_transaction`, `database_rollback_transaction`, `database_disconnect` |
| Ambiente | `environment_type`, `environment_is`, `environment_is_production`, `environment_is_development`, `environment_is_testing` |
| Form | `form_validator`, `form_validator_register_rule` |
| Globals | `globals_get`, `globals_add`, `globals_merge`, `globals_forget`, `globals_forget_many`, `globals_reset`, `globals_load_env`, `globals_env`, `globals_get_api_prefix`, `globals_is_api_request` |
| Idiomas | `lg`, `language_get`, `language_load`, `ld`, `language_detect`, `language_current`, `language_default` |
| Path | `path_root`, `path_app`, `path_app_bootable`, `path_app_helpers`, `path_app_routes`, `path_app_middlewares`, `path_app_controllers`, `path_app_models`, `path_app_views`, `path_app_views_pages`, `path_app_views_templates`, `path_system`, `path_system_interfaces`, `path_system_helpers`, `path_system_includes`, `path_public`, `path_storage`, `path_storage_sessions`, `path_storage_logs`, `path_languages`, `path_base`, `path_base_public`, `site_url` |
| Response | `response_redirect`, `response_html`, `response_text`, `response_json`, `response_xml`, `response_file` |
| Sessao | `session_start_safe`, `session_has`, `session_get`, `session_set`, `session_set_many`, `session_forget`, `session_clear`, `session_save`, `session_destroy_safe`, `session_regenerate`, `session_driver`, `session_is`, `session_is_files`, `session_is_db`, `session_is_none` |
| View | `view_share`, `view_share_many`, `view_forget`, `view_forget_many`, `view_set_template`, `view_get_template`, `view_render_page`, `view_render_html`, `view_globals` |

## Decisoes rapidas

| Necessidade | Use |
| --- | --- |
| Renderizar pagina | `view_render_page()` + `response_html()` |
| Retornar JSON | `response_json()` |
| Redirecionar | `response_redirect()` |
| Gerar URL absoluta | `site_url()` |
| Gerar caminho de asset | `path_base_public()` |
| Buscar traducao | `lg()` |
| Consultar varias linhas | `database_select()` |
| Consultar uma linha | `database_select_row()` |
| Executar insert/update/delete | `database_statement()` |
| Validar formulario | `form_validator()` |
| Ler sessao web | `session_get()` / `session_has()` |
| Compartilhar variavel global com views | `view_share()` |
| Inicializacao leve global | Classe em `app/Bootable` implementando `IBootable` |

## Sumario dos documentos

| Nome | Descricao | Documento |
| --- | --- | --- |
| Arquitetura e bootstrap | Objetivo, stack, estrutura, ciclo de requisicao e convencoes globais. | [01-arquitetura-bootstrap.md](/storage/ia-context/mvc-references/01-arquitetura-bootstrap.md) |
| Configuracao, rotas e URLs | `.env`, rotas web/API, `BASE_PATH`, assets e geracao de URL. | [02-configuracao-rotas-urls.md](/storage/ia-context/mvc-references/02-configuracao-rotas-urls.md) |
| Camadas MVC | Controllers, models, views, templates e fluxo para criar paginas. | [03-camadas-mvc.md](/storage/ia-context/mvc-references/03-camadas-mvc.md) |
| Idiomas e documentacao dinamica | Sistema de traducoes, prefixes de `languages/*` e home como documentacao. | [04-idiomas.md](/storage/ia-context/mvc-references/04-idiomas.md) |
| Banco, sessao e formularios | PDO, helpers `database_*`, drivers de sessao e `FormValidator`. | [05-banco-sessao-formularios.md](/storage/ia-context/mvc-references/05-banco-sessao-formularios.md) |
| Respostas, middlewares e bootables | Helpers `response_*`, middlewares e inicializacoes leves globais. | [06-respostas-middlewares-bootables.md](/storage/ia-context/mvc-references/06-respostas-middlewares-bootables.md) |
| Referencia de helpers | Tabela completa de helpers por area e decisoes rapidas de uso. | [07-helpers-referencia.md](/storage/ia-context/mvc-references/07-helpers-referencia.md) |
| Fluxos de trabalho | Checklists para novas paginas, APIs e novos projetos. | [08-fluxos-de-trabalho.md](/storage/ia-context/mvc-references/08-fluxos-de-trabalho.md) |
| Erros e pontos de atencao | Logs, handlers, riscos conhecidos e regras especificas para agentes. | [09-erros-atencoes.md](/storage/ia-context/mvc-references/09-erros-atencoes.md) |

## Home como documentacao dinamica

A view:

```text
app/views/pages/home.php
```

funciona como documentacao dinamica do framework.

Ela:

- usa `lg(...)` para buscar textos em `languages/doc/*`;
- exibe um resumo do framework;
- define um array `$docs` com classes, metodos, exemplos e descricoes;
- renderiza essas entradas como secoes HTML `<details>`.

Ao adicionar novas classes publicas ao framework, atualize:

```text
app/views/pages/home.php
languages/doc/en.json
languages/doc/pt-br.json
```

## Principio principal

Este projeto deve continuar sendo um **mini-framework simples, previsivel e direto**.

Ao implementar qualquer mudanca, prefira a menor alteracao correta, mantendo o padrao existente e evitando abstracoes desnecessarias.
