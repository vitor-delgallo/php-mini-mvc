# Document System Routes and Home Location

## Goal

Document the new system route layer, request detection helpers, and the new documentation home URL.

## Required Context

- Read this plan first.
- Then read `storage/ia-context/mvc.md` for the project conventions needed to implement the task.
- Also inspect:
  - `app/views/pages/home.php` or `system/views/pages/home.php`
  - `languages/doc/en.json`
  - `languages/doc/pt-br.json`
  - `system/Config/Globals.php`
  - `system/helpers/globals.php`
  - `storage/ia-context/mvc-references/02-configuration-routes-urls.md`
  - `storage/ia-context/mvc-references/07-helper-reference.md`
  - `storage/ia-context/mvc-references/helpers/04-system-config-globals.md`

## Documentation Updates

Update project context and references to explain:

- app web routes live in `app/routes/web.php`;
- app API routes live in `app/routes/api.php` and use `/api`;
- system web routes live in `system/routes/web.php` and use `/web-system`;
- system API routes live in `system/routes/api.php` and use `/api-system`;
- the framework documentation home now lives at `/web-system`;
- app `/` redirects to `/web-system`;
- `BASE_PATH` applies to all route prefixes.

## Home Documentation Updates

Add home documentation entries for the new `System\Config\Globals` methods:

- `getSystemWebPrefix()`
- `getSystemApiPrefix()`
- `isSystemWebRequest()`
- `isSystemApiRequest()`

Add helper alternatives:

- `globals_get_system_web_prefix()`
- `globals_get_system_api_prefix()`
- `globals_is_system_web_request()`
- `globals_is_system_api_request()`

Also update existing app API documentation if needed so it is clear that `/api` is for app APIs, not system APIs.

## Files to Update

Likely documentation files:

```text
storage/ia-context/mvc.md
storage/ia-context/mvc-references/02-configuration-routes-urls.md
storage/ia-context/mvc-references/03-mvc-layers.md
storage/ia-context/mvc-references/07-helper-reference.md
storage/ia-context/mvc-references/helpers/04-system-config-globals.md
languages/doc/en.json
languages/doc/pt-br.json
```

Update whichever home view currently owns the documentation: `app/views/pages/home.php` before the migration, or `system/views/pages/home.php` after it.

## Language Requirements

- Add English and Portuguese translation keys for all new home documentation text.
- Keep both language files aligned in meaning.
- Prefer concise descriptions that fit the existing home documentation layout.

## Acceptance Criteria

- Documentation points users to `/web-system` for framework docs.
- Route prefix docs cover app and system web/API routes.
- Home documentation includes the new Globals methods and helper alternatives.
- English and Portuguese translation files include every key used by the home page.
- The docs explain `BASE_PATH` behavior for `/web-system` and `/api-system`.
- No stale documentation says the app root `/` directly renders the documentation home.
