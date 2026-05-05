# Languages and Dynamic Documentation

## Language System

Main class:

```php
System\Core\Language
```

Helpers:

```php
lg('template.framework.name');
lg('system.database.connection.error.info', ['error' => $message]);
language_get('pages.users.profile');
language_load('pt-br');
ld('en');
language_current();
language_default();
language_detect();
```

## How It Works

1. Recursively search for files with the exact language name, such as `pt-br.json`.
2. Files at the root of `languages/` do not receive a prefix.
3. Files in subfolders receive a prefix based on the path.
4. All JSON files found are merged into a flat array.
5. Placeholders such as `{name}` are replaced by values from the array passed to `lg()`.

## Prefix Examples

```text
languages/pt-br.json                    -> back.home
languages/system/pt-br.json             -> system.http.404.title
languages/template/pt-br.json           -> template.framework.name
languages/pages/users/pt-br.json        -> pages.users.profile
languages/doc/pt-br.json                -> doc.body.details
```

## Loading Priority

1. Full requested/detected language, such as `pt-br`.
2. Short prefix, such as `pt`.
3. `DEFAULT_LANGUAGE`.
4. If nothing is found, translations are empty and the current language is `null`.

If the key does not exist, `lg()` returns `null`. In HTML, ensure the key exists or handle a fallback.

## Interface Text

When creating or changing visible text:

- prefer keys in `languages/*`;
- keep prefixes consistent with the folder;
- use `lg()` in views;
- avoid loose strings when the text is part of the public interface.
