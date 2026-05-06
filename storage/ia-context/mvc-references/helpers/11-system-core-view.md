# System\Core\View

Source: `system/Core/View.php`  
Helper source: `system/helpers/view.php`  
Namespace: `System\Core`

Renders page views or raw HTML inside a template, and manages variables shared with all views.

## Static Usage

```php
use System\Core\View;

View::setTemplate('template');
$html = View::render_page('home', ['title' => 'Home']);
```

## Helper Usage

```php
view_set_template('template');
$html = view_render_page('home', ['title' => 'Home']);
```

## Method And Helper Signatures

| Static method | Helper | Accepts | Returns |
| --- | --- | --- | --- |
| `View::share(string $key, mixed $value): void` | `view_share(string $key, mixed $value): void` | Variable name and value shared with all renders. | Nothing. |
| `View::shareMany(array $items): void` | `view_share_many(array $items): void` | Associative array of shared variables. | Nothing. |
| `View::forget(string $key): void` | `view_forget(string $key): void` | Shared variable name to remove. | Nothing. |
| `View::forgetMany(array $keys): void` | `view_forget_many(array $keys): void` | List of shared variable names to remove. | Nothing. |
| `View::clear(): void` | No helper. | No arguments. | Clears all shared variables. |
| `View::setTemplate(?string $relativePath = null): void` | `view_set_template(?string $relativePath = null): void` | Template path relative to `app/views/templates`, with or without `.php`. | Nothing. |
| `View::getTemplate(): string` | `view_get_template(): string` | No arguments. | Current normalized template path, defaulting to `/template.php`. |
| `View::render_page(string $page, array $data = []): string` | `view_render_page(string $page, array $data = []): string` | Page path relative to `app/views/pages`, without `.php`, plus local data. | Rendered HTML. |
| `View::render_html(string $html, array $data = []): string` | `view_render_html(string $html, array $data = []): string` | Raw HTML plus local data. | Rendered HTML inside the current template. |
| `View::getGlobals(): array` | `view_globals(): array` | No arguments. | Shared global variables. |

## Template Rules

- Template files live under `app/views/templates`.
- Page files live under `app/views/pages`.
- `setTemplate(null)` or an empty value resolves to `/template.php`.
- Passed `$data` is merged with shared globals and extracted into view scope.

## Notes

- The internal template expects `$page` or `$html` to be available and decide how to include/render content.
- Use `response_html(view_render_page(...))` in controllers when returning full pages.
