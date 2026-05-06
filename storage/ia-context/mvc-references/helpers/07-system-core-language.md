# System\Core\Language

Source: `system/Core/Language.php`  
Helper source: `system/helpers/language.php`  
Namespace: `System\Core`

Loads JSON translations from `languages/`, detects the active language, and returns translated strings with placeholder replacement.

## Static Usage

```php
use System\Core\Language;

Language::load('en');
$title = Language::get('doc.title');
```

## Helper Usage

```php
ld('en');
$title = lg('doc.title');
```

## Method And Helper Signatures

| Static method | Helper | Accepts | Returns |
| --- | --- | --- | --- |
| `Language::get(?string $key = null, ?array $replacements = null, ?string $lang = null): string|array|null` | `language_get(?string $key = null, array $replacements = null, ?string $lang = null): string|array|null`, `lg(?string $key = null, array $replacements = null, ?string $lang = null): string|array|null` | Optional translation key, placeholder replacements, and language override. | One translated string, all translations when `$key` is null, or `null`. |
| `Language::load(?string $lang = null): void` | `language_load(?string $lang = null): void`, `ld(?string $lang = null): void` | Optional language code. If null, uses browser detection and fallback. | Nothing. |
| `Language::detect(): ?string` | `language_detect(): ?string` | No arguments. Reads `HTTP_ACCEPT_LANGUAGE` and fallback env. | Detected language code or default language. |
| `Language::currentLang(): ?string` | `language_current(): ?string` | No arguments. | Currently loaded language code, or `null`. |
| `Language::defaultLang(): ?string` | `language_default(): ?string` | No arguments. Reads `DEFAULT_LANGUAGE`. | Default language code in lowercase, or `null`. |

## Translation File Rules

- The loader recursively finds files named exactly `<lang>.json`.
- Files in subdirectories receive a dot-prefix based on their relative path.
- Example: `languages/doc/en.json` can expose keys like `doc.title`.
- Load priority is full language code, short prefix, then `DEFAULT_LANGUAGE`.

## Placeholder Replacement

```json
{
  "welcome": "Welcome, {name}"
}
```

```php
echo lg('welcome', ['name' => 'Ana']);
```
