# Workflows

## Creating a New Page

Recommended flow:

1. Create the view at:

```text
app/views/pages/products/show.php
```

2. Create translations at:

```text
app/languages/pages/products/pt-br.json
app/languages/pages/products/en.json
```

3. Create the controller at:

```text
app/Controllers/Products.php
```

4. Register the route in:

```text
app/routes/web.php
```

Route example:

```php
$router->get('/products/{id}', [\App\Controllers\Products::class, 'show']);
```

Because the language file is at `app/languages/pages/products/pt-br.json`, final keys can be:

```text
app.pages.products.show.title
app.pages.products.not-found
```

## Creating an API Route

APIs do not use sessions and should return JSON, text, XML, files, or another PSR-7 response.

Example:

```php
// app/routes/api.php
$router->get('/health', function () {
    return response_json([
        'status' => 'ok',
        'env' => environment_type(),
    ]);
});
```

With `BASE_PATH=/php-mini-mvc`, the final URL is:

```text
/php-mini-mvc/api/health
```

## Checklist for New Projects

1. Copy `.env.example` to `.env`.
2. Adjust `APP_ENV`.
3. Adjust `BASE_PATH`.
4. Adjust `DEFAULT_LANGUAGE`.
5. Choose `SESSION_DRIVER`.
6. Choose `DB_DRIVER` or keep `none`.
7. Create routes in `app/routes/web.php` and/or `app/routes/api.php`.
8. Create controllers in `app/Controllers`.
9. Create models in `app/Models` when data is involved.
10. Create views in `app/views/pages`.
11. Create application translations in `app/languages/...` or framework/system translations in `system/languages/...`.
12. Use `response_*`, `view_*`, `lg`, `path_base_*`, and `database_*` as the main API.
13. Test with and without `BASE_PATH` when the project may run from a subdirectory.
14. Validate behavior in `development` and `production`.

## Recommended Order for Changes

1. Identify whether the change is web, API, database, view, language, session, or bootstrap related.
2. Open the matching reference document in `mvc-references/`.
3. Look for an existing helper or core class.
4. Make the smallest correct change.
5. Update translations if there is interface text.
6. Check `BASE_PATH` for links and assets.
7. Test the affected flow.
