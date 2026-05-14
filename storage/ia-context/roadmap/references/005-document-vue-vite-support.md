# Document Vue Vite Support

## Goal

Document the optional Vue + Vite workflow, including examples in the dynamic home documentation.

## Required Context

- Read this plan first.
- Then read `storage/ia-context/mvc.md` for the project conventions needed to implement the task.
- Also inspect:
  - `app/views/pages/home.php` or `system/views/pages/home.php`
  - `languages/doc/en.json`
  - `languages/doc/pt-br.json`
  - `storage/ia-context/mvc-references/03-mvc-layers.md`
  - `storage/ia-context/mvc-references/07-helper-reference.md`
  - `system/Core/View.php`
  - `system/helpers/view.php`

## Documentation Scope

Document Vue support as optional. The default MVC rendering flow remains PHP templates and PHP pages unless a route explicitly calls the Vue renderer.

## Implementation Plan

1. Add `view_render_vue()` to the home documentation in `app/views/pages/home.php` or `system/views/pages/home.php`, depending on where the documentation home lives.
2. Add the equivalent static method documentation for `System\Core\View::render_vue()`.
3. Update `languages/doc/en.json` and `languages/doc/pt-br.json` with the required descriptions and comments.
4. Add a dedicated MVC reference file, for example:
   - `storage/ia-context/mvc-references/10-vue-vite.md`
5. Update `storage/ia-context/mvc.md` to link the new Vue/Vite reference.
6. Update relevant helper reference documentation if `view_render_vue()` becomes a public helper.
7. Include examples for:
   - rendering a Vue page from a route;
   - passing props/data to the page;
   - using the default `main.js` entrypoint;
   - using a custom entrypoint relative to `resources/vue/`;
   - running under a non-empty `BASE_PATH`.

## Home Documentation Requirements

The home page should explain:

- Vue pages live in `resources/vue/pages/`.
- The page argument is relative to `resources/vue/pages/`.
- The entrypoint argument is relative to `resources/vue/`.
- `null` entrypoint means the default `main.js`.
- Data passed from PHP becomes props/input for the Vue page.
- Vue support is optional and only loaded for Vue-rendered pages.
- The main PHP template still controls the HTML layout.

## Example Route

```php
$router->get('/account', function () {
    return response_html(view_render_vue('account/Profile', [
        'title' => 'Account',
        'user' => ['name' => 'Vitor'],
    ]));
});
```

## Example Custom Entrypoint

```php
return response_html(view_render_vue(
    'admin/Users',
    ['title' => 'Users'],
    'admin.js'
));
```

## BASE_PATH Note

Document that Vue asset loading must use the same URL rules as regular public assets:

```php
path_base_public()
site_url()
```

Do not recommend hardcoded `/public/build/...` URLs.

## Acceptance Criteria

- The home page documents the Vue renderer and helper usage.
- English and Portuguese documentation strings are aligned in meaning.
- The MVC context links to a Vue/Vite reference document.
- Examples show default and custom entrypoint usage.
- The documentation states that Vue/Vite is optional.
- The documentation explains `BASE_PATH` compatibility.
