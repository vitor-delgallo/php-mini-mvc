# Camadas MVC

## Controllers

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

Padroes importantes:

- nao imprimir HTML diretamente no controller;
- nao usar `echo` como resposta final;
- retornar `response_html()`, `response_json()`, `response_redirect()` etc.;
- delegar consultas de dados para models;
- delegar HTML para views.

## Models

Models ficam em:

```text
app/Models
```

Namespace:

```php
namespace App\Models;
```

Models devem concentrar acesso a dados e regras simples de persistencia.

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

Use sempre parametros em queries SQL. Nao concatene dados de usuario diretamente no SQL.

## Views e templates

Renderizador principal:

```php
System\Core\View
```

Estrutura:

```text
app/views/pages/       Views de paginas
app/views/templates/   Templates/layouts
```

Template padrao:

```text
app/views/templates/template.php
```

Renderizacao de pagina:

```php
view_render_page('home', ['title' => 'Home']);
```

Metodo equivalente no core:

```php
View::render_page('home', $data);
```

Fluxo:

1. Mescla variaveis globais da view com `$data`.
2. Executa `extract(...)`.
3. Inclui o template atual.
4. O template inclui a pagina em `app/views/pages/$page.php`.

Renderizacao de HTML bruto:

```php
view_render_html('<h1>OK</h1>');
```

Use para casos simples, paginas de erro ou blocos HTML pequenos.

## Helpers de view

```php
view_share('title', 'Minha pagina');
view_share_many(['user' => $user]);
view_forget('user');
view_forget_many(['user', 'title']);
view_set_template('template');
view_get_template();
view_render_page('home', ['title' => 'Home']);
view_render_html('<h1>OK</h1>');
view_globals();
```

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
