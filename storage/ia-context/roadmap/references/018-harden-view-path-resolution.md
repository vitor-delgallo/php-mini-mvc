# Harden View Path Resolution

## Goal

Prevent local file inclusion risks in PHP view rendering by validating page and template paths before any `include`.

## Required Context

- Read this plan first.
- Then read `storage/ia-context/mvc.md`.
- Inspect `System\Core\View`, `app/views/templates/template.php`, `system/views/templates/template.php`, view helpers, and related home/helper documentation.
- Do not read `.env`; use only `.env.example` if configuration examples are needed.

## Problem

`View::setTemplate()` and `View::render_page()` currently accept relative names that are later included by the template layer. They normalize slashes, but do not reject traversal segments such as `..`, absolute paths, null bytes, or resolved files outside the intended view directories.

This is safe only when controllers pass developer-owned constants. It becomes a local file inclusion risk if a controller passes user input into page or template names.

## Implementation Plan

1. Add a private path normalizer/resolver in `System\Core\View` for PHP view paths.
2. Reject:
   - empty page names where a page is required;
   - null bytes;
   - absolute paths;
   - `.` and `..` path segments;
   - paths with unexpected extensions;
   - resolved files outside the expected base directory.
3. Keep valid nested paths such as `products/show`.
4. Apply validation to:
   - `View::setTemplate()`;
   - `View::render_page()`;
   - `View::render_system_page()`.
5. Prefer passing a resolved page file path into templates instead of making templates reconstruct the include path from `$page`.
6. Update app and system templates to include only the resolved page file path provided by `View`.
7. Preserve `View::render_html()` behavior.
8. Keep `View::render_vue()` using its existing Vue path validation, unless consolidation is clearly safe.

## Documentation Updates

Update:

- `storage/ia-context/mvc.md`;
- `storage/ia-context/mvc-references/03-mvc-layers.md`;
- `storage/ia-context/mvc-references/09-errors-cautions.md`;
- `system/views/pages/home.php` if method behavior descriptions change;
- `system/languages/doc/{en,pt-br,es}.json` when home text changes.

Document that view names must be developer-owned route/controller decisions, not raw user input.

## Tests And Verification

- Run `php -l` on changed PHP files.
- Add or run lightweight PHP checks for:
  - valid page: `home`;
  - valid nested page: `products/show`;
  - blocked traversal: `../config`;
  - blocked absolute path;
  - blocked null byte;
  - valid default template;
  - blocked template traversal.
- Render `/web-system` and an app page to confirm templates still work.
- Use Playwright to verify `/web-system` loads.

## Acceptance Criteria

- PHP view includes can only resolve files under their expected page/template directories.
- Existing valid app and system views continue to render.
- Invalid page/template paths fail cleanly before `include`.
- Documentation explains the safety rule.
- The corresponding README item is marked `[CONCLUDED]` only after implementation and verification.
