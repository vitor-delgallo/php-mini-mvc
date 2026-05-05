# Idiomas e documentacao dinamica

## Sistema de idiomas

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

## Funcionamento

1. Procura recursivamente arquivos com nome exato do idioma, por exemplo `pt-br.json`.
2. Arquivos na raiz de `languages/` nao recebem prefixo.
3. Arquivos em subpastas recebem prefixo baseado no caminho.
4. Todos os JSON encontrados sao mesclados em um array plano.
5. Placeholders como `{name}` sao substituidos pelo array passado a `lg()`.

## Exemplos de prefixo

```text
languages/pt-br.json                    -> back.home
languages/system/pt-br.json             -> system.http.404.title
languages/template/pt-br.json           -> template.framework.name
languages/pages/users/pt-br.json        -> pages.users.profile
languages/doc/pt-br.json                -> doc.body.details
```

## Prioridade de carregamento

1. Idioma completo solicitado/detectado, como `pt-br`.
2. Prefixo curto, como `pt`.
3. `DEFAULT_LANGUAGE`.
4. Se nada for encontrado, traducoes vazias e idioma atual `null`.

Se a chave nao existir, `lg()` retorna `null`. Em HTML, garanta que a chave existe ou trate fallback.

## Textos de interface

Ao criar ou alterar textos visiveis:

- prefira chaves em `languages/*`;
- mantenha prefixos coerentes com a pasta;
- use `lg()` nas views;
- evite strings soltas quando o texto fizer parte da interface publica.
