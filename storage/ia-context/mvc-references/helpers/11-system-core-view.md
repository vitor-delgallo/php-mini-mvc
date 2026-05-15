# System\Core\View

Source: `system/Core/View.php`  
Helper source: `system/helpers/view.php`  
Namespace: `System\Core`

Renders page views or raw HTML inside a template, and manages variables shared with all views.

## Static Usage

```php
use System\Core\View;

View::setTemplate('template');
$html = View::render_page('user-profile', ['user' => $user]);
```

## Helper Usage

```php
view_set_template('template');
$html = view_render_page('user-profile', ['user' => $user]);
$systemHtml = view_render_system_page('home');
$vueHtml = view_render_vue('account/Profile', ['title' => 'Account'], null, ['app.pages.account']);
view_clear();
```

## Method And Helper Signatures

| Static method | Helper | Accepts | Returns |
| --- | --- | --- | --- |
| `View::share(string $key, mixed $value): void` | `view_share(string $key, mixed $value): void` | Variable name and value shared with all renders. | Nothing. |
| `View::shareMany(array $items): void` | `view_share_many(array $items): void` | Associative array of shared variables. | Nothing. |
| `View::forget(string $key): void` | `view_forget(string $key): void` | Shared variable name to remove. | Nothing. |
| `View::forgetMany(array $keys): void` | `view_forget_many(array $keys): void` | List of shared variable names to remove. | Nothing. |
| `View::clear(): void` | `view_clear(): void` | No arguments. | Clears all shared variables. |
| `View::setTemplate(?string $relativePath = null): void` | `view_set_template(?string $relativePath = null): void` | Template path relative to `app/views/templates`, with or without `.php`. | Nothing. |
| `View::getTemplate(): string` | `view_get_template(): string` | No arguments. | Current normalized template path, defaulting to `/template.php`. |
| `View::render_page(string $page, array $data = []): string` | `view_render_page(string $page, array $data = []): string` | Page path relative to `app/views/pages`, without `.php`, plus local data. | Rendered HTML. |
| `View::render_system_page(string $page, array $data = []): string` | `view_render_system_page(string $page, array $data = []): string` | Page path relative to `system/views/pages`, without `.php`, plus local data. | Rendered HTML inside the system template. |
| `View::render_html(string $html, array $data = []): string` | `view_render_html(string $html, array $data = []): string` | Raw HTML plus local data. | Rendered HTML inside the current template. |
| `View::render_vue(string $page, array $data = [], ?string $entrypoint = null, array|string|null $i18nPrefixes = null, ?string $lang = null): string` | `view_render_vue(string $page, array $data = [], ?string $entrypoint = null, array|string|null $i18nPrefixes = null, ?string $lang = null): string` | Vue page relative to `resources/vue/pages`, props data, optional entrypoint relative to `resources/vue`, optional i18n prefixes, and optional language override. | Rendered HTML inside the current template. |
| `View::getGlobals(): array` | `view_globals(): array` | No arguments. | Shared global variables. |

## Template Rules

- Template files live under `app/views/templates`.
- Page files live under `app/views/pages`.
- System template files live under `system/views/templates`.
- System page files live under `system/views/pages`.
- Vue page files live under `resources/vue/pages`.
- `setTemplate(null)` or an empty value resolves to `/template.php`.
- Passed `$data` is merged with shared globals and extracted into view scope.
- Vue `$data` is serialized as props/input for the Vue page, and `null` entrypoint resolves to `main.js`.
- Vue i18n prefixes are serialized into the boot payload only for Vue renders. When `SYSTEM_TOKEN` is configured, `resources/vue/main.js` fetches `/api-system/i18n` with `X-System-Token` and provides `t(key)` to components.

## Notes

- The internal template expects `$page` or `$html` to be available and decide how to include/render content.
- Use `response_html(view_render_page(...))` in controllers when returning full pages.
- Use `response_html(view_render_system_page(...))` in system controllers when returning framework-owned pages such as `/web-system`.
- Use `response_html(view_render_vue(...))` only for routes that intentionally opt in to Vue/Vite.
- Vue/Vite asset URLs must preserve `BASE_PATH` compatibility through `path_base_public()` and `site_url()` rules.
