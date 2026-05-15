# MVC Layers

## Controllers

Controllers live in:

```text
app/Controllers
```

Namespace:

```php
namespace App\Controllers;
```

Controllers must return a PSR-7 response, usually through `response_*()` helpers.

Framework-owned controllers can live in:

```text
system/Controllers
```

System controllers use the `System\Controllers` namespace and are registered from `system/routes/*`.

Recommended example:

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

Important patterns:

- do not print HTML directly in the controller;
- do not use `echo` as the final response;
- return `response_html()`, `response_json()`, `response_redirect()`, etc.;
- delegate data queries to models;
- delegate HTML to views.

## Models

Models live in:

```text
app/Models
```

Namespace:

```php
namespace App\Models;
```

Models should concentrate data access and simple persistence rules.

Example:

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

Always use parameters in SQL queries. Do not concatenate user data directly into SQL.

## Views and Templates

Main renderer:

```php
System\Core\View
```

Structure:

```text
app/views/pages/          Application page views
app/views/templates/      Application templates/layouts
system/views/pages/       Framework/system page views
system/views/templates/   Framework/system templates/layouts
```

Default template:

```text
app/views/templates/template.php
```

Page rendering:

```php
view_render_page('user-profile', ['user' => $user]);
```

Equivalent core method:

```php
View::render_page('home', $data);
```

Flow:

1. Merge global view variables with `$data`.
2. Run `extract(...)`.
3. Include the current template.
4. The template includes the page in `app/views/pages/$page.php`.

System pages are rendered by system controllers through `View::render_system_page()`. The framework documentation home lives at:

```text
system/views/pages/home.php
```

It is served through `system/routes/web.php` at `/web-system`. The app root `/` redirects there.

Raw HTML rendering:

```php
view_render_html('<h1>OK</h1>');
```

Use this for simple cases, error pages, or small HTML blocks.

Optional Vue rendering:

```php
return response_html(view_render_vue('account/Profile', [
    'title' => 'Account',
    'user' => ['name' => 'Vitor'],
]));
```

Vue rendering is opt-in. PHP pages and PHP templates remain the default MVC flow unless a route explicitly calls `view_render_vue()` or `View::render_vue()`.

Rules:

- Vue pages live in `resources/vue/pages`.
- The page name is relative to `resources/vue/pages`, with or without `.vue`.
- The entrypoint is relative to `resources/vue`; `null` uses `main.js`.
- Data passed from PHP becomes props for the Vue page component.
- The PHP template still owns the HTML shell, layout, footer, and asset loading.
- Public asset URLs must stay compatible with `BASE_PATH`; use `path_base_public()` and `site_url()` instead of hardcoded `/public/...` URLs.

## View Helpers

```php
view_share('title', 'My page');
view_share_many(['user' => $user]);
view_forget('user');
view_forget_many(['user', 'title']);
view_set_template('template');
view_get_template();
view_render_page('user-profile', ['user' => $user]);
view_render_html('<h1>OK</h1>');
view_render_vue('account/Profile', ['title' => 'Account']);
view_globals();
```

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
