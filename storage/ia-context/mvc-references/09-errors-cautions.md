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

- in `production`, errors should not be displayed;
- in `development` and `testing`, errors may be displayed and logged;
- do not expose sensitive details in production.

## Current Points of Attention

- `View::render_page()` and `template.php` include view files without explicit existence validation.
- `Language::get()` returns `null` when the key does not exist.
- `Response::json()` accepts a string as raw JSON, but does not automatically validate whether the string is valid JSON.
- Sessions must not be used in API routes.
- Bootables run on every request; do not put heavy logic in them.
- If the application is in a subdirectory, test all assets and links with `BASE_PATH`.
- The `Remove and Clean MVC` action in `/web-system` is destructive. It deletes contents from explicit app, Vue, language, log, session, and public asset folders, rewrites app routes, and should not be triggered during routine validation unless the user explicitly wants the app skeleton cleaned.

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
