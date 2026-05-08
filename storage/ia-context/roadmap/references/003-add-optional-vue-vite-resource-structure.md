# Add Optional Vue Vite Resource Structure

## Goal

Add an optional Vue + Vite frontend structure under `resources/vue/` without changing the default PHP MVC workflow for projects that do not use Vue.

## Required Context

- Read this plan first.
- Then read `storage/ia-context/mvc.md` for the project conventions needed to implement the task.
- Also inspect:
  - `.env.example`
  - `system/Core/Path.php`
  - `system/helpers/path.php`
  - `app/views/templates/template.php`

## Required Structure

Create the Vue source directory at the project root:

```text
resources/
  vue/
    App.vue
    main.js
    pages/
```

`resources/vue/pages/` is where Vue page components will live. The MVC Vue renderer will receive a page path relative to this directory.

## Implementation Plan

1. Create `resources/vue/pages/`.
2. Create `resources/vue/App.vue` as the default Vue shell.
3. Create `resources/vue/main.js` as the default Vite entrypoint.
4. Make `App.vue` load the requested page component inside its standard app container.
5. Make `main.js` read boot data rendered by PHP, including:
   - the Vue page path;
   - the props/data passed by PHP;
   - optional metadata needed by the Vue app.
6. Use `import.meta.glob('./pages/**/*.vue')` or an equivalent Vite-supported pattern for page loading.
7. Normalize page names so both `dashboard/Index` and `dashboard/Index.vue` can point to `resources/vue/pages/dashboard/Index.vue`.
8. Add only the minimum Node/Vite files needed if they do not already exist:
   - `package.json`
   - `vite.config.js`
9. Keep Vue/Vite optional. PHP-only projects must continue working without installing Node dependencies.

## Vite Requirements

- Use Vue through `@vitejs/plugin-vue`.
- Use `resources/vue/main.js` as the default input.
- Build assets into `public/build/`.
- Enable a Vite manifest so PHP can load built JS and CSS from the template.
- Configure the Vite `base` using the app `BASE_PATH` value when possible, so built assets work when the project runs from a subdirectory.

## BASE_PATH Rules

- Do not hardcode `/public/build/...`.
- Built asset URLs must work with `BASE_PATH=/php-mini-mvc`.
- Prefer paths that match the framework convention:
  - public assets use `path_base_public()`;
  - absolute application URLs use `site_url()`.

## Optional Behavior

- The framework must not require Vue when rendering normal PHP pages.
- The template must only load Vue/Vite assets when the Vue renderer asks for a Vue component.
- Missing Node dependencies should not affect routes that do not call the Vue renderer.

## Acceptance Criteria

- `resources/vue/App.vue`, `resources/vue/main.js`, and `resources/vue/pages/` exist.
- Vue page loading is based on paths relative to `resources/vue/pages/`.
- Vite can build the default entrypoint into `public/build/`.
- The generated asset paths support `BASE_PATH`.
- Existing PHP view rendering remains unchanged for non-Vue pages.
