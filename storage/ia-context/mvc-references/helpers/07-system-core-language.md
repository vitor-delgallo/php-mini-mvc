# System\Core\Language

Source: `system/Core/Language.php`  
Helper source: `system/helpers/language.php`  
Namespace: `System\Core`

Loads JSON translations from `app/languages/` and `system/languages/`, detects the active language, and returns translated strings with placeholder replacement.

## Static Usage

```php
use System\Core\Language;

Language::load('en');
$details = Language::get('system.doc.body.details');
$translations = Language::getByPrefix('app.pages', 'en');
$prefix = Language::normalizePrefix('app.pages');
```

## Helper Usage

```php
ld('en');
$details = lg('system.doc.body.details');
$translations = language_get_by_prefix('app.pages', 'en');
$prefix = language_normalize_prefix('app.pages');
```

## Method And Helper Signatures

| Static method | Helper | Accepts | Returns |
| --- | --- | --- | --- |
| `Language::get(?string $key = null, ?array $replacements = null, ?string $lang = null): string|array|null` | `language_get(?string $key = null, array $replacements = null, ?string $lang = null): string|array|null`, `lg(?string $key = null, array $replacements = null, ?string $lang = null): string|array|null` | Optional translation key, placeholder replacements, and language override. | One translated string, all translations when `$key` is null, or `null`. |
| `Language::getByPrefix(string $prefix, ?string $lang = null): array` | `language_get_by_prefix(string $prefix, ?string $lang = null): array` | Translation key prefix with or without trailing dot, and optional language override. | Flat array of matching translations with full keys preserved. |
| `Language::normalizePrefix(string $prefix): string` | `language_normalize_prefix(string $prefix): string` | Translation key prefix with or without dots at the edges. | Empty string or the normalized prefix with exactly one trailing dot. |
| `Language::load(?string $lang = null): void` | `language_load(?string $lang = null): void`, `ld(?string $lang = null): void` | Optional language code. If null, uses browser detection and fallback. | Nothing. |
| `Language::detect(): ?string` | `language_detect(): ?string` | No arguments. Reads `HTTP_ACCEPT_LANGUAGE` and fallback env. | Detected language code or default language. |
| `Language::currentLang(): ?string` | `language_current(): ?string` | No arguments. | Currently loaded language code, or `null`. |
| `Language::defaultLang(): ?string` | `language_default(): ?string` | No arguments. Reads `DEFAULT_LANGUAGE`. | Default language code in lowercase, or `null`. |

## Translation File Rules

- The loader recursively finds files named exactly `<lang>.json` under `app/languages/` and `system/languages/`.
- App files receive the `app.` source prefix; system files receive the `system.` source prefix.
- Files in subdirectories receive a dot-prefix based on their relative path after the source prefix.
- Keys already starting with the source prefix are not double-prefixed.
- Example: `system/languages/doc/en.json` can expose keys like `system.doc.body.details`.
- Load priority is full language code, short prefix, then `DEFAULT_LANGUAGE`.
- Prefix filtering uses the same normalization as `Language::normalizePrefix()` / `language_normalize_prefix()`: `app.pages` becomes `app.pages.` and full keys are preserved in the returned array.

## Placeholder Replacement

```json
{
  "welcome": "Welcome, {name}"
}
```

```php
echo lg('app.welcome', ['name' => 'Ana']);
```

For application text, place the JSON under `app/languages/` and call keys such as `app.pages.users.profile`. For framework text, documentation, templates, validation, and core errors, place the JSON under `system/languages/` and call keys such as `system.template.framework.name` or `system.form_validator.error.required`.
