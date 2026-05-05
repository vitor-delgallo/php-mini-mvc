# Arquitetura e bootstrap

## Objetivo

O **PHP Mini MVC** e um mini-framework em PHP 8.2+ criado para aplicacoes enxutas:

- landing pages;
- sites institucionais;
- sistemas pequenos;
- APIs simples;
- projetos com front-end tradicional em HTML, CSS e JavaScript.

A proposta e manter uma estrutura simples, previsivel, com poucas dependencias e facil de adaptar. Antes de adicionar bibliotecas novas, sempre verifique se ja existe helper, classe de core ou padrao interno que resolva o problema.

## Stack

- **PHP 8.2+**
- **Composer**
- **PSR-4** para `App\` e `System\`
- **miladrahimi/phprouter** para rotas
- **Laminas Diactoros** para respostas PSR-7
- **vlucas/phpdotenv** para `.env`
- **PDO** para banco de dados

Nao converta o projeto para Laravel, Symfony, Slim, React, Vue, Angular ou qualquer outro framework maior sem solicitacao explicita.

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

## Convencoes gerais

- Respeite o MVC proprio existente.
- Preserve a estrutura `app/`, `system/`, `public/`, `languages/` e `storage/`.
- Use `App\...` para codigo da aplicacao.
- Use `System\...` para codigo do framework.
- Controllers devem retornar `Psr\Http\Message\ResponseInterface`.
- Views devem ser simples e focadas em apresentacao.
- Models devem concentrar acesso a dados.
- Helpers procedurais sao a API curta recomendada para views, controllers e middlewares.
- Evite dependencias grandes para problemas pequenos.
- Nao use jQuery, React, Vue ou Angular sem necessidade explicita.
- Prefira Bootstrap 5 e JavaScript vanilla para front-end tradicional.
- Use prepared statements sempre que houver SQL.

## Ciclo de requisicao

Entrada da aplicacao:

```text
public/index.php
```

O servidor deve apontar o document root para `public/` ou redirecionar todas as requisicoes para esse arquivo.

Fluxo geral:

1. Carrega `../vendor/autoload.php`.
2. Inclui `system/includes/error_handlers.php`.
3. Executa `Globals::loadEnv()`.
4. Le variaveis do `.env`.
5. Detecta se a requisicao e API com `Globals::isApiRequest()`.
6. Ajusta exibicao de erros conforme `APP_ENV`.
7. Carrega helpers internos de `system/helpers`.
8. Carrega helpers do app conforme `APP_HELPERS_AUTOLOAD`.
9. Configura sessao:
   - web: usa `system/includes/session_handlers.php`;
   - API: desativa cookies e usa `System\Session\NULLHandler`.
10. Conecta banco automaticamente se `DB_DRIVER` for valido.
11. Executa classes bootaveis em `app/Bootable`.
12. Carrega rotas:
   - web: `app/routes/web.php`;
   - API: `app/routes/api.php` com prefixo `/api`.
13. Despacha a rota pelo `RouterLoader`.
14. Em caso de `RouteNotFoundException`, retorna HTML 404.
15. Em outros erros, retorna HTML 500; fora de producao, exibe detalhes e grava log diario.

## Ordem recomendada para agentes

1. Leia `storage/ia-context/mvc.md`.
2. Abra apenas os documentos de referencia relacionados a tarefa.
3. Identifique quais arquivos realmente precisam ser alterados.
4. Procure helper, classe de core ou padrao existente antes de criar codigo novo.
5. Preserve o MVC proprio e a compatibilidade com `BASE_PATH`.
6. Teste o comportamento no escopo afetado.
