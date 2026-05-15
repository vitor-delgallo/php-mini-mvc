# Fetch System I18n From Vue Entrypoint

## Goal

Make the Vue entrypoint fetch translations from the protected system i18n API so Vue pages can reuse the PHP MVC language files.

## Required Context

- Read this plan first.
- Then read `storage/ia-context/mvc.md` for the project conventions needed to implement the task.
- Also inspect:
  - `resources/vue/main.js`, if it exists
  - `resources/vue/App.vue`, if it exists
  - `app/views/templates/template.php`
  - `system/Core/View.php`
  - `storage/ia-context/roadmap/references/004-add-vue-render-pipeline-to-mvc.md`
  - `storage/ia-context/roadmap/references/011-add-protected-system-i18n-api.md`

## Desired Behavior

When a page is rendered through the Vue renderer, `resources/vue/main.js` should:

- read boot data rendered by PHP;
- discover the i18n API URL;
- discover the translation prefix or prefixes requested by the page;
- send the system token in a header;
- fetch translations before mounting or expose a loading-safe state;
- make translations available to `App.vue` and page components.

## PHP Boot Data

Extend the Vue render boot payload to include i18n settings only for Vue pages.

Recommended fields:

```json
{
  "i18n": {
    "enabled": true,
    "endpoint": "/php-mini-mvc/api-system/i18n",
    "prefixes": ["app.pages"],
    "lang": "en",
    "token": "..."
  }
}
```

Use `path_base()` or `site_url()` rules so the endpoint works with `BASE_PATH`.

## Security Note

The browser must receive the token if frontend fetches this API directly. Document that this token protects a framework utility endpoint, not private user data.

If stronger protection is needed later, create a server-side proxy or authenticated app endpoint instead of exposing sensitive translations through this system API.

## Vue Implementation Plan

1. In `main.js`, read the PHP boot payload from the template.
2. If i18n is disabled or no token is provided, mount Vue without remote translations.
3. For each configured prefix, call the system i18n endpoint using `fetch`.
4. Send the token through `X-System-Token`.
5. Merge all returned translation maps.
6. Provide a small translation helper to Vue components, for example:
   - `app.provide('t', t)`;
   - `app.config.globalProperties.$t = t`;
   - or a lightweight composable.
7. Keep fallback behavior simple: when a key is missing, return the key itself.
8. Keep Vue/Vite optional. PHP-only pages must not load this code.

## Template Requirements

The PHP template should only render Vue i18n boot data when rendering a Vue page.

Do not expose `SYSTEM_TOKEN` on non-Vue pages.

## Documentation Updates

Update:

- Vue/Vite documentation;
- home documentation for the Vue renderer, if it shows i18n options;
- `.env.example` notes for `SYSTEM_TOKEN`;
- language documentation explaining Vue can request translations by prefix.

## Acceptance Criteria

- Vue pages can fetch translations from `/api-system/i18n`.
- The fetch works with `BASE_PATH`.
- The token is sent in a header.
- Missing or disabled i18n does not break mounting Vue.
- Vue components can call a documented translation helper.
- PHP-only pages are unaffected.
- Documentation includes an example Vue translation fetch or component usage.
