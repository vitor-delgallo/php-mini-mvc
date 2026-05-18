# Roadmap Workflow

This file controls project planning and execution.

## Core Rule

- Do not infer missing requirements in roadmap work.
- Planning may contain open questions from the AI.
- Any unresolved open question blocks approval and execution.
- If a user says to approve a plan without answering existing questions, ask again before approval. Do not mark it approved and do not execute it.
- If the user wants the AI to decide, the roadmap must record the exact delegated decision and the user must approve that recorded decision before execution.

## Status Tags

- `[PLANNING]`: the task is still being shaped, may contain AI questions, and must not be executed.
- `[APPROVED]`: the only status that makes a task actionable.
- `[CONCLUDED]`: the task has been completed and must not be selected as next work.
- `[EXAMPLE]`: documentation example only. It shows the expected format and must never be executed as project work.

## Workflow

- For any request related to roadmap, planning, next tasks, backlog, or implementation order, read this file first.
- Detailed plans live in `storage/ia-context/roadmap/references/`.
- Use numeric kebab-case names such as `001-create-login.md` or `002-configure-user-session.md`.
- Every plan must be self-contained and readable by an AI that knows nothing except the context files named in the plan.
- Every plan must list required context files to read first.
- Every plan must define a `Skills To Use` section.
- For `Skills To Use`, inspect all Codex global skills and project-installed skills, then list only the skills that make sense for the task described by the plan.
- If a skill listed in a plan is not found, stop the plan, do not approve or execute it, and tell the user that the plan stopped because the missing skill was not found.
- Every plan must list application files or directories to inspect before editing.
- Every plan must list likely files to create or change. If exact files are not knowable yet, list the decision that must be answered first.
- Every plan must define expected QA evidence before delivery.
- At the end of executed work, update `storage/ia-context` files when implementation changes project standards, database shape, roadmap status, or reusable knowledge.

## Required Plan Sections

Each roadmap reference must include these sections:

- `Status`
- `Goal`
- `Context Files To Read First`
- `Skills To Use`
- `Files Or Directories To Inspect`
- `Open Questions For User`
- `Implementation Steps`
- `Expected QA Evidence`
- `Files Likely To Change`
- `Approval Notes`

## Open Questions Protocol

- Use `Open Questions For User` for any unclear requirement.
- Keep questions concrete and answerable.
- Do not hide assumptions in implementation steps.
- Do not convert a `[PLANNING]` item to `[APPROVED]` while `Open Questions For User` contains unanswered items.
- If answers change the plan, update the plan first, then ask for approval of the updated plan.

## Skills Protocol

- Check Codex global skills and project-installed skills before approving or executing a roadmap item.
- Match skills to the concrete work in the plan, such as Vue work, Vite work, browser verification, document generation, spreadsheet work, or other specialized tasks.
- Do not list unrelated skills only because they are installed.
- If a listed skill is unavailable, keep the item planning-only, stop the plan, and report the missing skill to the user.

## Items

- `[EXAMPLE]` [Example roadmap plan](references/001-example.md): demonstrates the expected structure for a plan, including required context reads, skills selection, open questions, execution steps, QA evidence, and approval notes. It is not real project work.
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
- [CONCLUDED] [Extract System I18n Auth Middleware](references/013-extract-system-i18n-auth-middleware.md): Move `/api-system/i18n` token authentication from `System\Controllers\I18n` into `System\Middlewares\SystemI18nAuth` and apply it on the system route.
- [CONCLUDED] [Normalize Prefixed Root Routes](references/016-normalize-prefixed-root-routes.md): Let routes declared as `/` inside app or system prefixes also match the prefix URL without requiring duplicate `''` route declarations.
- [CONCLUDED] [Add Dangerous App Cleanup Tool](references/014-add-dangerous-app-cleanup-tool.md): Add a SweetAlert-protected home action that cleans app MVC files, optional Vue pages, app languages, logs, sessions, selected public assets, and resets app routes.
- [CONCLUDED] [Refactor Away System Helper Runtime Usage](references/015-refactor-away-system-helper-runtime-usage.md): Refactor runtime code to use static system classes directly and add `SYSTEM_HELPERS_AUTOLOAD` with app-helper-style selection.
- [CONCLUDED] [Add Multiple Database Connections](references/017-add-multiple-database-connections.md): Add suffixed `DB_*_<NAME>` environment groups, runtime-defined connection configs, and optional `SESSION_DB` selection for lazy named PDO connections.
- [CONCLUDED] [Harden View Path Resolution](references/018-harden-view-path-resolution.md): Validate PHP view page and template paths before include operations to prevent traversal-based local file inclusion.
- [PLANNING] [Harden Site URL Host Handling](references/019-harden-site-url-host-handling.md): Stop trusting forwarded host/proto headers by default and add explicit trusted-proxy handling for `siteURL()`.
- [PLANNING] [Harden Session Cookie Options](references/020-harden-session-cookie-options.md): Configure safe PHP session cookie defaults before `session_start()` while preserving API stateless behavior.
- [CONCLUDED] [Replace DB Session Encryption](references/021-replace-db-session-encryption.md): Replace deterministic AES-CBC DB session encryption with authenticated encryption and clear migration rules.
