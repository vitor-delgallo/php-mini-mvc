# Improve Example Page Visuals

## Goal

Improve the visual quality of the existing example pages while preserving the framework's simple PHP MVC style, and refactor the user-facing example page to use the optional Vue renderer as a concrete Vue page example.

The documentation home must stay as it is for this task.

## Required Context

- Read this plan first.
- Then read `storage/ia-context/mvc.md` for the project conventions needed to implement the task.
- Also inspect:
  - `app/views/templates/template.php`
  - `app/Controllers/User.php`
  - `app/routes/web.php`
  - `app/views/pages/user-profile.php`
  - `resources/vue/App.vue`
  - `resources/vue/main.js`
  - `resources/vue/pages/`
  - `languages/doc/en.json`
  - `languages/doc/pt-br.json`
  - `languages/pages/users/en.json`
  - `languages/pages/users/pt-br.json`
  - `storage/ia-context/mvc-references/10-vue-vite.md`
  - `system/Core/View.php`
  - `system/helpers/view.php`

## Current Example Pages

The currently available example pages are:

- the documentation home page, which must remain unchanged by this task;
- the user profile page at `app/views/pages/user-profile.php`;
- the optional Vue page directory at `resources/vue/pages/`;
- the shared template at `app/views/templates/template.php`.

The user profile route should become the first real Vue-rendered example page used by the system. Keep the home documentation as the PHP documentation page.

## Implementation Plan

1. Keep the documentation home unchanged. Do not redesign, move, or convert the home page in this task.
2. Improve the shared template only where needed for normal PHP pages and Vue-rendered pages to look clean and intentional.
3. Keep the template lightweight and framework-agnostic:
   - no external CSS framework dependency;
   - no new JavaScript dependency for basic PHP layout;
   - no required frontend build step for PHP-only pages.
4. Refactor the user profile example to render through Vue:
   - create a Vue page such as `resources/vue/pages/users/Profile.vue`;
   - update `App\Controllers\User::showPage()` to call `view_render_vue('users/Profile', ...)`;
   - wrap the render with `response_html()`;
   - keep the not-found response behavior unchanged.
5. Pass user profile data and translated labels from PHP into Vue props:
   - user `id`, `name`, and `email`;
   - labels from `lg()` or `System\Core\Language::get()`;
   - a home/back URL generated with existing path or URL helpers.
6. Keep user data escaped/safe:
   - rely on Vue text interpolation for user values;
   - do not use `v-html` for user-provided data.
7. Improve the user profile visual design:
   - present user fields in a compact profile panel or table-like layout;
   - include a clear back/home link;
   - ensure desktop and mobile layouts remain readable.
8. Preserve existing helper usage and route behavior outside the user profile render change.
9. Keep all public asset paths compatible with `BASE_PATH` by using existing path helpers if assets are added.

## Visual Constraints

- Do not convert the project into a frontend framework.
- Do not introduce Bootstrap, Tailwind, React, or another frontend framework.
- Use the existing optional Vue/Vite support only for the user profile example.
- Do not convert the documentation home to Vue.
- Keep cards and containers modest; this is framework documentation, not a marketing page.
- Ensure text and code blocks remain readable on mobile screens.
- Use semantic HTML where it fits the current structure.

## Acceptance Criteria

- Existing example pages look cleaner on desktop and mobile.
- The documentation home remains unchanged and usable as dynamic framework documentation.
- The user profile route renders through `view_render_vue()` and acts as a working Vue example page.
- The Vue user profile page keeps translated labels and safe user data rendering.
- The default PHP template still renders PHP pages and raw HTML as before.
- No helper or bootstrap behavior changes are introduced by this task.
- PHP-only pages continue working without opting in to Vue rendering.
