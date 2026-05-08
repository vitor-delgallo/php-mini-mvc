# Add Protected System I18n API

## Goal

Create a protected system API route that exposes selected translations for Vue files, using the framework language system.

## Required Context

- Read this plan first.
- Then read `storage/ia-context/mvc.md` for the project conventions needed to implement the task.
- Also inspect:
  - `system/Core/Language.php`
  - `system/Config/Globals.php`
  - `system/helpers/globals.php`
  - `system/routes/api.php`
  - `public/index.php`
  - `.env.example`
  - `storage/ia-context/roadmap/references/007-add-system-routes-and-request-detection.md`

## Required Environment Variable

Add `SYSTEM_TOKEN` to `.env` and `.env.example`.

Rules:

- The token is fixed and configured by the project owner.
- If `SYSTEM_TOKEN` is empty or undefined, disable the i18n API.
- Document the variable in `.env.example` using the same comment style already used there.

## Language API

Add a language method that reuses the existing load/get logic but filters by prefix.

Recommended API:

```php
Language::getByPrefix(string $prefix, ?string $lang = null): array
```

Recommended helper:

```php
language_get_by_prefix(string $prefix, ?string $lang = null): array
```

Behavior:

- load the requested or detected language using the existing fallback rules;
- read all loaded translations;
- return only keys that start with the requested prefix;
- preserve full keys in the response to match PHP `lg()` usage;
- normalize the prefix so `app.pages` and `app.pages.` both work.

## System Controller

Create a controller under `system/`, for example:

```text
system/Controllers/I18n.php
```

The controller should:

- read the requested prefix from route params or query params;
- optionally read a `lang` query parameter;
- validate the `SYSTEM_TOKEN`;
- return JSON through `response_json()`;
- return 404 or 403 when the API is disabled or the token is invalid;
- avoid sessions because this is a system API route.

## Token Validation

Use a request header, not a query string, for the token.

Recommended header:

```text
X-System-Token: <SYSTEM_TOKEN>
```

Acceptance may also allow:

```text
Authorization: Bearer <SYSTEM_TOKEN>
```

Use constant-time comparison when possible.

## Route

Add the route to `system/routes/api.php`.

Recommended route:

```text
GET /i18n
```

Final URL with system API prefix:

```text
/api-mvc-system/i18n?prefix=app.pages
```

With `BASE_PATH=/php-mini-mvc`:

```text
/php-mini-mvc/api-mvc-system/i18n?prefix=app.pages
```

## Response Shape

Recommended success response:

```json
{
  "lang": "en",
  "prefix": "app.pages.",
  "translations": {
    "app.pages.users.profile": "User Profile"
  }
}
```

## Acceptance Criteria

- `SYSTEM_TOKEN` is documented in `.env.example`.
- The system i18n API is disabled when `SYSTEM_TOKEN` is missing or empty.
- The endpoint requires a valid system token.
- The endpoint returns translations filtered by prefix.
- The endpoint uses the framework language loader and fallback rules.
- The endpoint works under the `/api-mvc-system` route prefix and respects `BASE_PATH`.
- Documentation explains the route, token, prefix parameter, and response shape.
