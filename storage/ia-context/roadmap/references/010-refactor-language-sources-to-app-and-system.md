# Refactor Language Sources to App and System

## Goal

Move the root `languages/` directory into separate application and system language directories, then prefix loaded translation keys by source.

## Required Context

- Read this plan first.
- Then read `storage/ia-context/mvc.md` for the project conventions needed to implement the task.
- Also inspect:
  - `system/Core/Language.php`
  - `system/helpers/language.php`
  - `system/Core/Path.php`
  - `system/helpers/path.php`
  - all files currently under `languages/`
  - `storage/ia-context/mvc-references/04-languages.md`
  - `storage/ia-context/mvc-references/helpers/07-system-core-language.md`

## New Directory Structure

Replace the current root `languages/` directory with:

```text
app/languages/
system/languages/
```

Recommended migration:

- app-facing strings go to `app/languages/`;
- framework, documentation, template, validation, and core error strings go to `system/languages/`.

## Prefix Rules

Loaded keys must receive a source prefix:

- files under `app/languages/` use prefix `app.`;
- files under `system/languages/` use prefix `system.`;
- if a key already starts with the source prefix, do not add it again.

Subdirectory prefixes should still work after the source prefix.

Examples:

```text
app/languages/en.json + "back.home" -> app.back.home
app/languages/pages/users/en.json + "profile" -> app.pages.users.profile
system/languages/doc/en.json + "body.details" -> system.doc.body.details
system/languages/en.json + "system.http.404.title" -> system.http.404.title
```

## Implementation Plan

1. Add path methods and helpers for the new directories:
   - `Path::appLanguages()` / `path_app_languages()`
   - `Path::systemLanguages()` / `path_system_languages()`
2. Refactor `Language` so it scans both language roots instead of `Path::languages()`.
3. Keep language fallback behavior:
   - full language code;
   - short language prefix;
   - `DEFAULT_LANGUAGE`.
4. Apply source prefixes after computing the relative subfolder prefix.
5. Keep placeholder replacement behavior unchanged.
6. Clear or split language file caches by source and language to avoid stale merges.
7. Move JSON files to the correct new folder.
8. Update all translation usages in PHP:
   - `app` strings should use `app.*`;
   - `system` strings should use `system.*`;
   - documentation home strings should use `system.doc.*`;
   - template strings should use `system.template.*` if the template is system-owned.
9. Remove the root `languages/` directory after migration.

## Important Usage Updates

Likely changes include:

- `lg("pages.users.profile")` -> `lg("app.pages.users.profile")`
- `lg("back.home")` -> `lg("app.back.home")`
- `lg("doc.body.details")` -> `lg("system.doc.body.details")`
- `lg("template.framework.name")` -> `lg("system.template.framework.name")`

Do not double-prefix keys that already begin with `system.`.

## Acceptance Criteria

- Runtime translations load from `app/languages/` and `system/languages/`.
- The old root `languages/` directory is no longer required.
- App keys are available through `app.*`.
- System keys are available through `system.*`.
- Existing core error keys that already start with `system.` still work without becoming `system.system.*`.
- Home, template, user profile, validators, errors, and controllers use the new keys.
- Language reference documentation is updated for the new directory and prefix rules.
