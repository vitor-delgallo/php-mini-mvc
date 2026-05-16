# Extract System I18n Auth Middleware

## Goal

Move protected system i18n API authentication out of `System\Controllers\I18n` and into a dedicated system middleware under `system/Middlewares`.

The controller should focus on request parameters, language prefix filtering, and JSON response data. The middleware should own `SYSTEM_TOKEN` validation and request authentication for i18n routes.

## Required Context

- Read this plan first.
- Then read `storage/ia-context/mvc.md` for the project conventions needed to implement the task.
- Also inspect:
  - `system/Controllers/I18n.php`
  - `system/routes/api.php`
  - `system/Config/Globals.php`
  - `system/Core/Response.php`
  - `app/Middlewares/Example.php`
  - `storage/ia-context/roadmap/references/011-add-protected-system-i18n-api.md`
  - `storage/ia-context/roadmap/references/012-fetch-system-i18n-from-vue-entrypoint.md`

## Current Problem

`System\Controllers\I18n` currently validates `SYSTEM_TOKEN` directly. That mixes endpoint behavior with access control:

- disabled API handling when `SYSTEM_TOKEN` is empty;
- `X-System-Token` header parsing;
- `Authorization: Bearer <token>` parsing;
- constant-time token comparison;
- forbidden response handling.

This should be reusable middleware because authentication belongs in the route layer, not inside the translation controller.

## Implementation Plan

1. Create:

```text
system/Middlewares/SystemI18nAuth.php
```

Recommended namespace and class:

```php
namespace System\Middlewares;

class SystemI18nAuth
```

2. Move the i18n authentication logic from `System\Controllers\I18n` into the middleware.

3. Middleware behavior:

- read `SYSTEM_TOKEN` through `System\Config\Globals`;
- when the token is missing or empty, return JSON `404` with `{"error":"system_i18n_disabled"}`;
- accept `X-System-Token: <token>`;
- also accept `Authorization: Bearer <token>`;
- compare tokens with `hash_equals`;
- when token is missing or invalid, return JSON `403` with `{"error":"forbidden"}`;
- call `$next($request)` only after authentication succeeds.

4. Keep middleware API compatible with the current route middleware pattern:

```php
public function handle(\Psr\Http\Message\ServerRequestInterface $request, \Closure $next)
```

5. Update `system/routes/api.php` so only the i18n routes use the middleware.

Recommended shape:

```php
$router->group([
    'middleware' => [\System\Middlewares\SystemI18nAuth::class],
], function (Router $router) use ($systemI18n) {
    $router->get('/i18n', $systemI18n);
    $router->get('/i18n/{prefix}', $systemI18n);
});
```

6. Refactor `System\Controllers\I18n`:

- remove private token/auth methods;
- remove `System\Config\Globals` import if it becomes unused;
- keep prefix validation, language selection, `Language::normalizePrefix()`, `Language::getByPrefix()`, and response shape unchanged.

## Documentation Updates

Update:

- `storage/ia-context/mvc.md`;
- `storage/ia-context/mvc-references/02-configuration-routes-urls.md`;
- `storage/ia-context/mvc-references/04-languages.md`;
- `storage/ia-context/mvc-references/06-responses-middlewares-bootables.md`;
- helper/system reference docs if they list system middleware or protected i18n behavior.

Docs should explain:

- `SYSTEM_TOKEN` protection for `/api-system/i18n` is enforced by `System\Middlewares\SystemI18nAuth`;
- the controller is responsible only for i18n request/response behavior;
- the token is still sent with `X-System-Token` by Vue i18n fetches;
- `Authorization: Bearer <token>` remains accepted.

## Tests

- Run `php -l` on changed PHP files.
- Run a direct HTTP check for:
  - missing/empty token config returns 404 for `/api-system/i18n`;
  - missing request token returns 403;
  - invalid token returns 403;
  - valid `X-System-Token` returns 200 and translations;
  - valid `Authorization: Bearer` returns 200.
- Confirm non-i18n system API routes remain unaffected.
- Use Playwright to verify a Vue page still receives i18n translations through the protected endpoint.

## Acceptance Criteria

- `system/Middlewares/SystemI18nAuth.php` exists and owns i18n auth.
- `System\Controllers\I18n` no longer validates tokens directly.
- `system/routes/api.php` applies the middleware to i18n routes.
- Existing response status codes and JSON error shapes remain compatible.
- Vue i18n fetches from `resources/vue/main.js` still work.
- Documentation names the middleware as the auth owner.
- Roadmap README is updated to mark only this task as `[CONCLUDED]` after implementation and verification.
