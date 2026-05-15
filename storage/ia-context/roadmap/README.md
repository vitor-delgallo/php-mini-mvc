# Roadmap

This directory stores the implementation roadmap for the project.

## How to Use This Roadmap

1. Choose the task from the roadmap summary below.
2. Open the linked task plan in `storage/ia-context/roadmap/references/`.
3. Read the specific task plan before starting any implementation work.
4. After reading the task plan, check `storage/ia-context/mvc.md` for the project context needed to implement that task.
5. Treat each task plan as standalone. Do not assume knowledge from previous roadmap plans.

## File Rules

- Keep this README as the roadmap summary.
- Store detailed task plans in `storage/ia-context/roadmap/references/`.
- Write all roadmap files in English.
- Keep each file small, with a maximum of 200 lines.
- Update this README whenever a new roadmap task plan is added.
- Each task plan must include enough context to be executed independently.

## Roadmap Summary

- [CONCLUDED] [Document Missing DB Helpers in Home](references/001-document-missing-db-helpers-in-home.md): Add missing `System\Core\Database` helper documentation to the home view, whether it is still in `app/views/pages/home.php` or already moved to `system/views/pages/home.php`.
- [CONCLUDED] [Improve Home Function Documentation](references/002-improve-home-function-documentation.md): Improve the home page documentation so each function explains purpose, usage, return behavior, and practical constraints more clearly.
- [CONCLUDED] [Add Optional Vue Vite Resource Structure](references/003-add-optional-vue-vite-resource-structure.md): Add the optional `resources/vue/` structure, default `App.vue`, default `main.js`, page folder, and minimal Vite build conventions.
- [CONCLUDED] [Add Vue Render Pipeline to MVC](references/004-add-vue-render-pipeline-to-mvc.md): Add `view_render_vue()` and `View::render_vue()` so MVC routes can render Vue pages through the existing template system.
- [CONCLUDED] [Document Vue Vite Support](references/005-document-vue-vite-support.md): Document the optional Vue/Vite workflow, examples, `BASE_PATH` behavior, and home page helper reference.
- [CONCLUDED] [Improve Example Page Visuals](references/006-improve-example-page-visuals.md): Improve the example visuals while keeping the home unchanged and refactoring the user profile example to use the optional Vue renderer.
- [CONCLUDED] [Add System Routes and Request Detection](references/007-add-system-routes-and-request-detection.md): Add `system/routes/web.php`, `system/routes/api.php`, system route prefixes, request detection helpers, and bootstrap route loading.
- [CONCLUDED] [Move Documentation Home to System Route](references/008-move-documentation-home-to-system-route.md): Move the documentation home into the system layer, serve it at `/web-system`, and redirect the app root to the new URL.
- [CONCLUDED] [Document System Routes and Home Location](references/009-document-system-routes-and-home-location.md): Update the home documentation, language files, and MVC references for system routes and the new documentation home location.
- [CONCLUDED] [Refactor Language Sources to App and System](references/010-refactor-language-sources-to-app-and-system.md): Move translations into `app/languages/` and `system/languages/`, applying `app.` and `system.` source prefixes without double-prefixing.
- [CONCLUDED] [Add Protected System I18n API](references/011-add-protected-system-i18n-api.md): Add a token-protected system API endpoint that returns translations filtered by prefix for Vue and other system consumers.
- [CONCLUDED] [Fetch System I18n From Vue Entrypoint](references/012-fetch-system-i18n-from-vue-entrypoint.md): Make the Vue entrypoint fetch translations from the protected system i18n API and provide them to Vue components.
- [Add Dangerous App Cleanup Tool](references/013-add-dangerous-app-cleanup-tool.md): Add a SweetAlert-protected home action that cleans app MVC files, optional Vue pages, app languages, logs, sessions, selected public assets, and resets app routes.
- [Refactor Away System Helper Runtime Usage](references/014-refactor-away-system-helper-runtime-usage.md): Refactor runtime code to use static system classes directly and add `SYSTEM_HELPERS_AUTOLOAD` with app-helper-style selection.
