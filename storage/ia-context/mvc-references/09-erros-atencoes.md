# Erros e pontos de atencao

## Logs e erros

Arquivo:

```text
system/includes/error_handlers.php
```

Ele registra:

- `set_error_handler` para warnings, notices e erros capturaveis;
- `register_shutdown_function` para erros fatais.

Logs sao gravados em:

```text
storage/logs/YYYY-MM-DD.log
```

Regras:

- em `production`, erros nao devem ser exibidos;
- em `development` e `testing`, erros podem ser exibidos e logados;
- nao exponha detalhes sensiveis em producao.

## Pontos de atencao do estado atual

- `View::render_page()` e `template.php` incluem arquivos de view sem validacao explicita de existencia.
- `Language::get()` retorna `null` quando a chave nao existe.
- `Response::json()` aceita string como JSON bruto, mas nao valida automaticamente se a string e JSON valido.
- Sessao nao deve ser usada em rotas API.
- Bootables rodam em toda requisicao; nao coloque logica pesada neles.
- Se a aplicacao estiver em subdiretorio, teste todos os assets e links com `BASE_PATH`.

## Regras para agentes de IA

Ao receber uma tarefa neste projeto:

1. Leia `storage/ia-context/mvc.md`.
2. Identifique quais arquivos realmente precisam ser alterados.
3. Antes de criar nova funcao, procure helper ou classe existente.
4. Antes de criar nova dependencia, tente resolver com core existente ou PHP puro.
5. Preserve o MVC proprio.
6. Preserve a compatibilidade com `BASE_PATH`.
7. Preserve o sistema de idiomas quando houver textos de interface.
8. Nao reestruture o projeto inteiro para uma tarefa pequena.
9. Entregue alteracoes pequenas, testaveis e consistentes.

Regras especificas:

- Controllers devem retornar `ResponseInterface`.
- Views podem usar helpers procedurais.
- Models devem concentrar queries, regras de negocio e acesso a dados.
- APIs devem ser stateless ou usar token, nao sessao.
- SQL deve usar parametros/prepared statements.
- Assets devem usar `path_base_public()`.
- URLs absolutas devem usar `site_url()`.
- Textos traduziveis devem ir para `languages/*`.
- Arquivos de idioma em subpasta recebem prefixo pelo caminho.
- Nao use nome de view vindo diretamente do usuario.

## Principio principal

Este projeto deve continuar sendo um **mini-framework simples, previsivel e direto**.

Ao implementar qualquer mudanca, prefira a menor alteracao correta, mantendo o padrao existente e evitando abstracoes desnecessarias.
