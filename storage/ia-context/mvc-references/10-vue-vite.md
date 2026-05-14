# Optional Vue And Vite Support

Vue/Vite support is optional. The default MVC flow remains PHP routes, PHP controllers, PHP page views, and PHP templates.

Use Vue only when a route explicitly renders a Vue page through `view_render_vue()` or `System\Core\View::render_vue()`.

## Involved Files

```text
resources/vue/App.vue
resources/vue/main.js
resources/vue/pages/
vite.config.js
package.json
app/views/templates/template.php
system/Core/View.php
system/helpers/view.php
```

Build output:

```text
public/build/.vite/manifest.json
public/build/assets/
```

## Rendering From A Route

Use the helper from a web route and wrap the HTML string in `response_html()`:

```php
$router->get('/account', function () {
    return response_html(view_render_vue('account/Profile', [
        'title' => 'Account',
        'user' => ['name' => 'Vitor'],
    ]));
});
```

Equivalent static class usage:

```php
use System\Core\View;

$html = View::render_vue('account/Profile', [
    'title' => 'Account',
    'user' => ['name' => 'Vitor'],
]);
```

## Page And Props Rules

- Vue pages live in `resources/vue/pages/`.
- The `$page` argument is relative to `resources/vue/pages/`.
- The `$page` argument may be passed with or without `.vue`.
- The `$data` array is serialized into the boot payload and passed to the Vue page as props.
- `resources/vue/App.vue` loads pages with `import.meta.glob('./pages/**/*.vue')`.
- The PHP template still controls the full HTML shell and decides where the Vue mount is printed.

Example file:

```text
resources/vue/pages/account/Profile.vue
```

Expected route argument:

```php
view_render_vue('account/Profile', ['title' => 'Account']);
```

## Default Entrypoint

When the third argument is `null`, the renderer uses:

```text
resources/vue/main.js
```

`main.js` reads the JSON boot payload from the PHP template, mounts Vue on `#php-mini-mvc-vue`, and passes:

```text
page
pageProps
meta
```

## Custom Entrypoint

Pass a third argument when a route needs a different Vite entrypoint:

```php
return response_html(view_render_vue(
    'admin/Users',
    ['title' => 'Users'],
    'admin.js'
));
```

The entrypoint is relative to:

```text
resources/vue/
```

If a custom entrypoint is used in production, it must also be present in the Vite manifest.

## Development And Production Assets

Development can use `VITE_DEV_SERVER` from `.env`:

```dotenv
VITE_DEV_SERVER=http://localhost:5173
```

Without `VITE_DEV_SERVER`, the PHP template reads:

```text
public/build/.vite/manifest.json
```

Run the production build before using manifest-based rendering:

```bash
npm run build
```

## BASE_PATH Compatibility

Vue assets must follow the same URL rules as regular public assets.

Use:

```php
path_base_public()
site_url()
```

Do not hardcode URLs like:

```text
/public/build/assets/app.js
```

When `BASE_PATH=/php-mini-mvc`, build asset URLs must keep that prefix.

## Cautions

- Do not convert normal PHP pages to Vue unless the task explicitly asks for Vue rendering.
- Do not place business logic in Vue components; keep server-side data decisions in controllers and models.
- Do not bypass `View::render_vue()` path normalization.
- Do not recommend hardcoded public asset URLs.
- Keep Vue/Vite docs separate from the default PHP rendering flow so the framework remains lightweight by default.
