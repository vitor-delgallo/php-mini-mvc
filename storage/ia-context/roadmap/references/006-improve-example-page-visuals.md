# Improve Example Page Visuals

## Goal

<<<<<<< HEAD
Improve the visual quality of the existing example pages while preserving the framework's simple PHP MVC style, and refactor the user-facing example page to use the optional Vue renderer as a concrete Vue page example.

The documentation home must stay as it is for this task.
=======
Improve the visual quality of the existing example pages while preserving the framework's simple PHP MVC style.
>>>>>>> 81b3ee498e46a7e496bc5aaa6567f970b707703f

## Required Context

- Read this plan first.
- Then read `storage/ia-context/mvc.md` for the project conventions needed to implement the task.
- Also inspect:
  - `app/views/templates/template.php`
<<<<<<< HEAD
  - `app/Controllers/User.php`
  - `app/routes/web.php`
  - `app/views/pages/user-profile.php`
  - `resources/vue/App.vue`
  - `resources/vue/main.js`
  - `resources/vue/pages/`
=======
  - `app/views/pages/home.php` or `system/views/pages/home.php`
  - `app/views/pages/user-profile.php`
>>>>>>> 81b3ee498e46a7e496bc5aaa6567f970b707703f
  - `languages/doc/en.json`
  - `languages/doc/pt-br.json`
  - `languages/pages/users/en.json`
  - `languages/pages/users/pt-br.json`
<<<<<<< HEAD
  - `storage/ia-context/mvc-references/10-vue-vite.md`
  - `system/Core/View.php`
  - `system/helpers/view.php`
=======
>>>>>>> 81b3ee498e46a7e496bc5aaa6567f970b707703f

## Current Example Pages

The currently available example pages are:

<<<<<<< HEAD
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
=======
- the documentation home page;
- the user profile page at `app/views/pages/user-profile.php`;
- the shared template at `app/views/templates/template.php`.

If the documentation home is moved into `system/` by another task, apply the same visual improvement rules to the new system home view instead of the old app copy.

## Implementation Plan

1. Improve the shared template styling so example pages look cleaner and more intentional.
2. Keep the template lightweight and framework-agnostic:
   - no external CSS framework dependency;
   - no build step;
   - no JavaScript dependency for basic layout.
3. Improve spacing, typography, code blocks, details/summary states, links, and responsive behavior.
4. Make the documentation home easier to scan:
   - better section spacing;
   - clearer method cards/details;
   - more readable code examples;
   - visible hierarchy between class and method sections.
5. Improve the user profile example page:
   - present user fields in a small profile panel or table-like layout;
   - keep escaped output with `htmlspecialchars`;
   - keep translated labels through `lg()`.
6. Preserve existing helper usage and template render logic.
7. Keep all public asset paths compatible with `BASE_PATH` by using existing path helpers if assets are added.
>>>>>>> 81b3ee498e46a7e496bc5aaa6567f970b707703f

## Visual Constraints

- Do not convert the project into a frontend framework.
<<<<<<< HEAD
- Do not introduce Bootstrap, Tailwind, React, or another frontend framework.
- Use the existing optional Vue/Vite support only for the user profile example.
- Do not convert the documentation home to Vue.
=======
- Do not introduce Bootstrap, Tailwind, Vue, React, or a bundler for this task.
>>>>>>> 81b3ee498e46a7e496bc5aaa6567f970b707703f
- Keep cards and containers modest; this is framework documentation, not a marketing page.
- Ensure text and code blocks remain readable on mobile screens.
- Use semantic HTML where it fits the current structure.

## Acceptance Criteria

- Existing example pages look cleaner on desktop and mobile.
<<<<<<< HEAD
- The documentation home remains unchanged and usable as dynamic framework documentation.
- The user profile route renders through `view_render_vue()` and acts as a working Vue example page.
- The Vue user profile page keeps translated labels and safe user data rendering.
- The default PHP template still renders PHP pages and raw HTML as before.
- No helper or bootstrap behavior changes are introduced by this task.
- PHP-only pages continue working without opting in to Vue rendering.
=======
- The documentation home remains usable as dynamic framework documentation.
- The user profile page keeps translated labels and escaped user data.
- The default PHP template still renders PHP pages and raw HTML as before.
- No route, controller, helper, or bootstrap behavior changes are introduced by this visual-only task.
>>>>>>> 81b3ee498e46a7e496bc5aaa6567f970b707703f
