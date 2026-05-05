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
app/views/pages/       Page views
app/views/templates/   Templates/layouts
```

Default template:

```text
app/views/templates/template.php
```

Page rendering:

```php
view_render_page('home', ['title' => 'Home']);
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

Raw HTML rendering:

```php
view_render_html('<h1>OK</h1>');
```

Use this for simple cases, error pages, or small HTML blocks.

## View Helpers

```php
view_share('title', 'My page');
view_share_many(['user' => $user]);
view_forget('user');
view_forget_many(['user', 'title']);
view_set_template('template');
view_get_template();
view_render_page('home', ['title' => 'Home']);
view_render_html('<h1>OK</h1>');
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
languages/pages/products/pt-br.json
languages/pages/products/en.json
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

Because the language file is at `languages/pages/products/pt-br.json`, final keys can be:

```text
pages.products.show.title
pages.products.not-found
```
