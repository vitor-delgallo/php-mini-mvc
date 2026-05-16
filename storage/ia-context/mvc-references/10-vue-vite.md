# Optional Vue And Vite Support

Vue/Vite support is optional. The default MVC flow remains PHP routes, PHP controllers, PHP page views, and PHP templates.

Use Vue only when a route explicitly renders a Vue page through `System\Core\View::render_vue()` or the optional `view_render_vue()` helper.

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

Use the static renderer from a web route and wrap the HTML string in `Response::html()`:

```php
use System\Core\Response;
use System\Core\View;

$router->get('/account', function () {
    return Response::html(View::render_vue('account/Profile', [
        'title' => 'Account',
        'user' => ['name' => 'Vitor'],
    ], null, ['app.pages.account']));
});
```

Optional helper shortcut when system helpers are enabled:

```php
return response_html(view_render_vue('account/Profile', [
    'title' => 'Account',
    'user' => ['name' => 'Vitor'],
], null, ['app.pages.account']));
```

## Page And Props Rules

- Vue pages live in `resources/vue/pages/`.
- The `$page` argument is relative to `resources/vue/pages/`.
- The `$page` argument may be passed with or without `.vue`.
- The `$data` array is serialized into the boot payload and passed to the Vue page as props.
- The optional fourth argument accepts one i18n prefix or a list of prefixes requested by the Vue page.
- `resources/vue/App.vue` loads pages with `import.meta.glob('./pages/**/*.vue')`.
- The PHP template still controls the full HTML shell and decides where the Vue mount is printed.

Example file:

```text
resources/vue/pages/account/Profile.vue
```

Expected route argument:

```php
View::render_vue('account/Profile', ['title' => 'Account']);
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
translations
```

## Custom Entrypoint

Pass a third argument when a route needs a different Vite entrypoint:

```php
return Response::html(View::render_vue(
    'admin/Users',
    ['title' => 'Users'],
    'admin.js',
    ['app.pages.admin']
));
```

The entrypoint is relative to:

```text
resources/vue/
```

If a custom entrypoint is used in production, it must also be present in the Vite manifest.

## Vue I18n From MVC Translations

Vue pages can request translations from the protected system i18n API by passing one prefix or a list of prefixes as the fourth renderer argument:

```php
return Response::html(View::render_vue(
    'users/Profile',
    ['user' => $user],
    null,
    ['app.pages.users', 'app.back'],
    'en'
));
```

When `SYSTEM_TOKEN` is configured, the Vue boot payload includes:

```json
{
  "i18n": {
    "enabled": true,
    "endpoint": "/php-mini-mvc/api-system/i18n",
    "prefixes": ["app.pages.users", "app.back"],
    "lang": "en",
    "token": "..."
  }
}
```

`resources/vue/main.js` fetches each prefix before mounting, sends the token in `X-System-Token`, passes through `System\Middlewares\SystemI18nAuth`, merges the returned translation maps, and exposes:

```js
app.provide('t', t);
app.config.globalProperties.$t = t;
```

Example in a Vue component:

```vue
<script setup>
import { inject } from 'vue';

const t = inject('t', (key) => key);
</script>

<template>
  <h1>{{ t('app.pages.users.profile') }}</h1>
</template>
```

If i18n is disabled, the token is empty, a fetch fails, or a key is missing, Vue still mounts and `t(key)` returns the key itself.

Security note: frontend i18n fetches expose `SYSTEM_TOKEN` to the browser. Use this token only for framework utility endpoints such as translation subsets, not private user data. Use a server-side proxy or authenticated app endpoint for stronger protection.

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
- Do not expose `SYSTEM_TOKEN` on non-Vue pages. The template only prints Vue i18n boot data inside the Vue render branch.
