# Errors and Cautions

## Logs and Errors

File:

```text
system/includes/error_handlers.php
```

It registers:

- `set_error_handler` for warnings, notices, and catchable errors;
- `register_shutdown_function` for fatal errors.

Logs are written to:

```text
storage/logs/YYYY-MM-DD.log
```

Rules:

- in `production`, errors are logged but not displayed;
- in `development` and `testing`, errors are displayed and logged;
- do not expose sensitive details in production.

## Current Points of Attention

- `View::render_page()`, `View::render_system_page()`, and `View::setTemplate()` validate PHP view paths before include operations. Keep view names as developer-owned relative paths, not user-provided input.
- `Language::get()` returns `null` when the key does not exist.
- `Response::json()` accepts a string as raw JSON, but does not automatically validate whether the string is valid JSON.
- Sessions must not be used in API routes.
- Bootables run on every request; do not put heavy logic in them.
- If the application is in a subdirectory, test all assets and links with `BASE_PATH`.
- `Path::siteURL()` honours forwarded host/proto headers only from `TRUSTED_PROXIES`; the `Host` header itself remains client-controlled, so do not treat generated absolute URLs as a trusted canonical origin unless the application fixes it in its own configuration.
- `Language` reports an invalid translation JSON with an `E_USER_WARNING` (logged, and displayed outside production) and skips the file; `Language::get()` still returns `null` for its keys.
- `View::render()` extracts shared and page data with `EXTR_SKIP`: a variable named like a render argument (`page`, `html`, `data`) is ignored rather than overriding it.
- Session storage handlers are configured by the framework, but hardened cookie flags are not set by `system/includes/session_handlers.php` yet. Configure PHP session cookie settings for auth-sensitive apps.
- DB session encryption uses the versioned authenticated `v2` format when `SESSION_ENCRYPT_KEY` is filled. Older deterministic AES-CBC encrypted session rows are intentionally not readable and should be cleared after upgrading.
- The `Remove and Clean MVC` action in `/web-system` is destructive. It deletes contents from explicit app, Vue, language, log, session, and public asset folders, rewrites app routes, and should not be triggered during routine validation unless the user explicitly wants the app skeleton cleaned.
- The dangerous cleanup action is blocked by a direct manual safety `return` in `System\Controllers\Maintenance::cleanApp()`. Remove that return only when intentionally cleaning a fresh project skeleton, then add it back or otherwise protect the endpoint.

## Rules for AI Agents

When receiving a task in this project:

1. Read `../mvc.md`.
2. Identify which files actually need to change.
3. Before creating a new function, look for an existing helper or class.
4. Before creating a new dependency, try to solve it with the existing core or plain PHP.
5. Preserve the project's own MVC style.
6. Preserve `BASE_PATH` compatibility.
7. Preserve the language system when interface text is involved.
8. Do not restructure the whole project for a small task.
9. Deliver small, testable, consistent changes.

Specific rules:

- Controllers must return `ResponseInterface`.
- Views may use procedural helpers only when their autoload strategy enables them; framework runtime views should use static system classes directly.
- Models must concentrate queries, business rules, and data access.
- APIs should be stateless or use tokens, not sessions.
- SQL must use parameters/prepared statements.
- Assets must use `path_base_public()`.
- Absolute URLs must use `site_url()`.
- Translatable text must go to `app/languages/*` for application text or `system/languages/*` for framework/system text.
- Language keys receive the source prefix (`app.*` or `system.*`) plus any subfolder prefix.
- Do not use a view name that comes directly from the user.
- Do not invoke the dangerous cleanup endpoint while testing unrelated changes.

## Main Principle

This project should remain a **simple, predictable, direct mini-framework**.

When implementing any change, prefer the smallest correct change, keep the existing pattern, and avoid unnecessary abstractions.
