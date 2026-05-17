# Harden Session Cookie Options

## Goal

Apply secure PHP session cookie defaults before `session_start()` for web requests.

## Required Context

- Read this plan first.
- Then read `storage/ia-context/mvc.md`.
- Inspect `System\Config\Session`, `System\Core\Session`, `system/includes/session_handlers.php`, session helpers, `.env.example`, root READMEs, and session MVC references.
- Do not read `.env`.

## Problem

Sessions are started through `Session::start()` without central cookie hardening. The framework should set safe defaults such as `httponly`, `samesite`, `secure` when HTTPS is active, and `use_strict_mode` before `session_start()`.

## Implementation Plan

1. Add session option resolution to `System\Config\Session`.
2. Recommended environment contract:

```dotenv
# Session cookie SameSite mode: Lax | Strict | None
SESSION_COOKIE_SAMESITE=Lax

# Force secure session cookies. Leave blank for auto-detection from HTTPS.
SESSION_COOKIE_SECURE=

# HTTP-only session cookies should stay enabled.
SESSION_COOKIE_HTTPONLY=true

# Enable PHP session strict mode.
SESSION_STRICT_MODE=true
```

3. Apply options before `Session::start()` calls `session_start()`.
4. Keep API requests cookie-free through `NULLHandler`.
5. Auto-detect secure cookies from HTTPS when `SESSION_COOKIE_SECURE` is blank.
6. If `SameSite=None`, require secure cookies or document the browser requirement.
7. Preserve existing session helper method names.
8. Avoid changing session IDs or regenerating sessions outside explicit auth flows.

## Documentation Updates

Update:

- `.env.example`;
- `README.md`;
- `README.pt-br.md`;
- `storage/ia-context/mvc.md`;
- `storage/ia-context/mvc-references/05-database-session-forms.md`;
- `storage/ia-context/mvc-references/09-errors-cautions.md`;
- home docs/languages if session config is listed there.

Document that cookie options must be configured before sessions are started.

## Tests And Verification

- Run `php -l` on changed PHP files.
- Validate changed JSON files if any.
- Run PHP checks for config parsing:
  - default SameSite `Lax`;
  - secure auto-detection;
  - explicit secure true/false;
  - strict mode enabled by default.
- Run a small request/bootstrap check and inspect `session_get_cookie_params()`.
- Confirm API requests still use `NULLHandler` and do not start normal cookies.
- Use Playwright to open `/web-system`.

## Acceptance Criteria

- Session cookie params are set before `session_start()`.
- Defaults are safer while staying compatible with local HTTP development.
- API request session behavior remains stateless.
- Documentation lists the new session variables and expected behavior.
- The corresponding README item is marked `[CONCLUDED]` only after implementation and verification.
