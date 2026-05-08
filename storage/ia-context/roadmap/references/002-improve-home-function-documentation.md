# Improve Home Function Documentation

## Goal

Improve the documentation shown in the home view so each documented function is easier to understand and more useful to developers using the framework.

## Required Context

- Read this plan first.
- Then read `storage/ia-context/mvc.md` for the project conventions needed to implement the task.
- Also inspect:
  - `app/views/pages/home.php` or `system/views/pages/home.php`
  - `languages/doc/en.json`
  - `languages/doc/pt-br.json`
  - `storage/ia-context/mvc-references/07-helper-reference.md`
  - The relevant helper reference files under `storage/ia-context/mvc-references/helpers/`

## Current Problem

The home page already lists many framework classes and helpers, but several descriptions are short and do not fully explain:

- What the function is for.
- When a developer should use it.
- What kind of value it returns.
- Important behavior or limitations.
- How the helper alternative relates to the static class method.

## Implementation Plan

1. Review every documentation entry in the `$docs` array in the home view, whether it is in `app/views/pages/home.php` or `system/views/pages/home.php`.
2. For each function, compare the current example and description with the relevant system source or helper reference.
3. Improve the language strings in:
   - `languages/doc/en.json`
   - `languages/doc/pt-br.json`
4. Keep the `home.php` structure intact unless a small example change is needed for clarity.
5. Make examples more practical, but keep them short enough for the home page.
6. Prefer explaining behavior in the translation values rather than adding long hardcoded text to `home.php`.
7. Keep the documentation focused on usage, not internal implementation details unless they affect developers.

## Documentation Guidelines

- Start descriptions with the action the function performs.
- Mention return values when they are important for correct use.
- Mention safe usage rules for database queries, responses, sessions, and validators.
- Explain aliases clearly: the `alt` example should show the helper equivalent for the class method.
- Avoid repeating the same generic phrase across many functions.
- Keep each description concise enough to fit naturally in the existing home page layout.

## Suggested Areas to Improve

- Database helpers: clarify result shapes, transactions, and parameter binding.
- Session helpers: clarify driver behavior, persistence, regeneration, and API limitations where relevant.
- View helpers: clarify shared view data, template selection, and render methods.
- Response helpers: clarify return type and intended route/controller usage.
- Form validator helpers: clarify validation flow, errors, custom rules, and form state.
- Path helpers: clarify whether the value is a filesystem path, base path, or URL.
- Language helpers: clarify lookup, fallback, loading, and detection behavior.
- Globals helpers: clarify runtime config versus `.env` values.

## Acceptance Criteria

- The home documentation is clearer for every major function group.
- The English and Portuguese documentation strings remain aligned in meaning.
- Existing translation key names are preserved when possible.
- Any new translation keys are added to both language files.
- The home page still renders without missing translation keys or PHP warnings.
- Each function remains documented independently, without relying on another roadmap plan.
