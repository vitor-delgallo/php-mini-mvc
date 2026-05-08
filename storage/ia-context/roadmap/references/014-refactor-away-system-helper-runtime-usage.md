# Refactor Away System Helper Runtime Usage

## Goal

Refactor the MVC runtime to use static system classes directly instead of relying on helper functions from `system/helpers/`, then add an environment variable to control which system helpers are loaded.

## Required Context

- Read this plan first.
- Then read `storage/ia-context/mvc.md` for the project conventions needed to implement the task.
- Also inspect:
  - `public/index.php`
  - `system/Core/PHPAutoload.php`
  - `system/Core/FormValidator.php`
  - `app/routes/web.php`
  - `app/routes/api.php`
  - `app/Middlewares/Example.php`
  - `app/views/templates/template.php`
  - `app/views/pages/*.php`
  - `.env.example`

## New Environment Variable

Add `SYSTEM_HELPERS_AUTOLOAD` with the same behavior as `APP_HELPERS_AUTOLOAD`.

Recommended default:

```dotenv
SYSTEM_HELPERS_AUTOLOAD=true
```

Supported values should match the app helper loader:

- `true`, `1`, `all`, or `*`: load every helper in `system/helpers/`;
- a list such as `['response','view.php']`: load only selected system helpers;
- empty or disabled value: load no system helper files if a disabled mode is implemented.

Document it in `.env.example` using the current comment style.

## Runtime Refactor Scope

Replace helper calls in shipped framework/example runtime files with direct static class usage.

Examples:

- `lg(...)` -> `System\Core\Language::get(...)`
- `response_redirect(...)` -> `System\Core\Response::redirect(...)`
- `response_html(...)` -> `System\Core\Response::html(...)`
- `view_render_page(...)` -> `System\Core\View::render_page(...)`
- `view_render_html(...)` -> `System\Core\View::render_html(...)`
- `path_app_views_pages()` -> `System\Core\Path::appViewsPages()`
- `form_validator(...)` -> `new System\Core\FormValidator()` plus explicit setup when needed

Do not remove helper functions themselves. They remain optional public convenience APIs when loaded.

## Bootstrap Plan

1. Extract the app helper autoload parsing logic in `public/index.php` into a reusable local function or a small core method.
2. Apply the same parsing behavior to `SYSTEM_HELPERS_AUTOLOAD`.
3. Load system helpers according to `SYSTEM_HELPERS_AUTOLOAD`.
4. Load app helpers according to `APP_HELPERS_AUTOLOAD`.
5. Ensure bootstrap code itself does not require helpers to be loaded.

## Important Refactor Targets

Current helper-dependent areas include:

- `system/Core/FormValidator.php` validation messages;
- `app/Middlewares/Example.php` redirect example;
- `app/routes/web.php` home rendering and response;
- `app/views/templates/template.php` title, include path, and footer translations;
- `app/views/pages/user-profile.php`;
- `app/views/pages/home.php` or `system/views/pages/home.php`.

After language source refactor, use the new prefixed keys where appropriate.

## Documentation Updates

Update:

- `.env.example`;
- `storage/ia-context/mvc.md`;
- helper reference documentation;
- home documentation for helper loading behavior.

The docs should explain:

- system helpers are optional convenience wrappers;
- framework runtime code should use static classes directly;
- apps may still choose to load helpers for concise application code;
- `SYSTEM_HELPERS_AUTOLOAD` controls the system helper files independently from `APP_HELPERS_AUTOLOAD`.

## Acceptance Criteria

- Framework and shipped example runtime code no longer depends on system helper functions.
- The project still works when `SYSTEM_HELPERS_AUTOLOAD` loads no helpers.
- `SYSTEM_HELPERS_AUTOLOAD` supports the same selection style as `APP_HELPERS_AUTOLOAD`.
- Existing helper files remain available when explicitly loaded.
- `.env.example` documents the new variable.
- MVC context and home documentation explain the new helper-loading model.
