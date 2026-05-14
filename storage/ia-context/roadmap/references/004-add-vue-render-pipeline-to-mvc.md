# Add Vue Render Pipeline to MVC

## Goal

Add a Vue renderer to the MVC View layer that reuses the existing template rendering logic and mounts a Vue page inside the default `App.vue` shell.

## Required Context

- Read this plan first.
- Then read `storage/ia-context/mvc.md` for the project conventions needed to implement the task.
- Also inspect:
  - `system/Core/View.php`
  - `system/helpers/view.php`
  - `system/Core/Path.php`
  - `app/views/templates/template.php`
  - `resources/vue/App.vue` and `resources/vue/main.js`, if they already exist

## Public API

Add a renderer with this behavior:

```php
view_render_vue(string $page, array $data = [], ?string $entrypoint = null): string
```

Equivalent static method:

```php
System\Core\View::render_vue(string $page, array $data = [], ?string $entrypoint = null): string
```

Arguments:

- `$page`: path relative to `resources/vue/pages/`, with or without `.vue`.
- `$data`: props/data to pass to the Vue page.
- `$entrypoint`: path relative to `resources/vue/`; when `null`, use `main.js`.

## Implementation Plan

1. Add `View::render_vue()` to `system/Core/View.php`.
2. Add `view_render_vue()` to `system/helpers/view.php`.
3. Keep `View::render_vue()` thin and make it call the existing private `View::render()` method.
4. Pass Vue-specific render data into the template, for example:
   - selected Vue page component;
   - Vue props/data;
   - selected Vite entrypoint.
5. Do not duplicate template rendering or response generation logic.
6. Normalize `$page` and `$entrypoint`:
   - trim leading and trailing slashes;
   - normalize backslashes to forward slashes;
   - reject `..` path traversal;
   - allow `$page` with or without `.vue`;
   - default `$entrypoint` to `main.js`.
7. Make the renderer safe for nested Vue pages such as `admin/users/Index`.
8. Preserve existing `view_render_page()` and `view_render_html()` behavior.

## Template Integration

Update the example template render conditional in `app/views/templates/template.php`.

Current shape:

```php
if (!empty($page)) {
    include path_app_views_pages() . '/' . $page . '.php';
} elseif (!empty($html)) {
    echo $html;
}
```

Add an `elseif` for Vue render data. That branch should:

- render the Vue mount div;
- expose the selected page and props as JSON for `resources/vue/main.js`;
- load the configured Vite entrypoint;
- load CSS produced by the Vite manifest when using built assets.

## Asset Loading Rules

- Use `path_base_public()` when generating built asset URLs.
- Support `BASE_PATH` for direct routes and routes inside subdirectories.
- In development, support a Vite dev server only when explicitly configured.
- In production or normal PHP serving, read the Vite manifest from `public/build/`.
- If the manifest or built entrypoint is missing, fail with a clear developer-facing message only for Vue-rendered pages.

## Example Usage

```php
$router->get('/dashboard', function () {
    return response_html(view_render_vue('dashboard/Index', [
        'title' => 'Dashboard',
        'user' => ['name' => 'Vitor'],
    ]));
});
```

With a custom entrypoint:

```php
return response_html(view_render_vue('admin/Users', $data, 'admin.js'));
```

## Acceptance Criteria

- Controllers can return `response_html(view_render_vue(...))`.
- The Vue renderer reuses `View::render()`.
- Vue data is available to `main.js` and the requested page component.
- `BASE_PATH` works for built assets and direct route access.
- PHP pages and raw HTML rendering continue to work unchanged.
- Vue/Vite remains optional unless a route calls the Vue renderer.
