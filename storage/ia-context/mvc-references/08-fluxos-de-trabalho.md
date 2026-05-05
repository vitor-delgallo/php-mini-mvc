# Fluxos de trabalho

## Criando uma nova pagina

Fluxo recomendado:

1. Criar a view em:

```text
app/views/pages/products/show.php
```

2. Criar as traducoes em:

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

Como o arquivo de idioma esta em `languages/pages/products/pt-br.json`, as chaves finais podem ser:

```text
pages.products.show.title
pages.products.not-found
```

## Criando uma rota API

APIs nao usam sessao e devem retornar JSON, texto, XML, arquivo ou outra resposta PSR-7.

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

Com `BASE_PATH=/php-mini-mvc`, a URL final sera:

```text
/php-mini-mvc/api/health
```

## Checklist para novos projetos

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
11. Criar traducoes em `languages/...`.
12. Usar `response_*`, `view_*`, `lg`, `path_base_*` e `database_*` como API principal.
13. Testar com e sem `BASE_PATH`, quando o projeto puder rodar em subdiretorio.
14. Validar comportamento em `development` e `production`.

## Ordem recomendada para alteracoes

1. Identifique se a mudanca e web, API, banco, view, idioma, sessao ou bootstrap.
2. Abra o documento de referencia correspondente em `/storage/ia-context/mvc-references/`.
3. Procure helper ou classe de core existente.
4. Faça a menor alteracao correta.
5. Atualize traducoes se houver texto de interface.
6. Verifique `BASE_PATH` para links e assets.
7. Teste o fluxo afetado.
