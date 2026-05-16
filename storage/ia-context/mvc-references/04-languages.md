# Languages and Dynamic Documentation

## Language System

Main class:

```php
System\Core\Language
```

Helpers:

```php
lg('system.template.framework.name');
lg('system.database.connection.error.info', ['error' => $message]);
language_get('app.pages.users.profile');
language_get_by_prefix('app.pages', 'en');
language_normalize_prefix('app.pages');
language_load('pt-br');
ld('en');
language_current();
language_default();
language_detect();
```

## How It Works

1. Recursively search `app/languages/` and `system/languages/` for files with the exact language name, such as `pt-br.json`.
2. Files under `app/languages/` receive the source prefix `app.`.
3. Files under `system/languages/` receive the source prefix `system.`.
4. Files in subfolders also receive a prefix based on the relative path after the source prefix.
5. Keys that already start with the source prefix are not prefixed again.
6. All JSON files found are merged into a flat array.
7. Placeholders such as `{name}` are replaced by values from the array passed to `lg()`.

## Prefix Examples

```text
app/languages/pt-br.json                         -> app.back.home
app/languages/pages/users/pt-br.json             -> app.pages.users.profile
system/languages/pt-br.json                      -> system.http.404.title
system/languages/template/pt-br.json             -> system.template.framework.name
system/languages/doc/pt-br.json                  -> system.doc.body.details
system/languages/form_validator/pt-br.json       -> system.form_validator.error.required
```

## Loading Priority

1. Full requested/detected language, such as `pt-br`.
2. Short prefix, such as `pt`.
3. `DEFAULT_LANGUAGE`.
4. If nothing is found, translations are empty and the current language is `null`.

If the key does not exist, `lg()` returns `null`. In HTML, ensure the key exists or handle a fallback.

## Prefix Filtering API

Use `Language::getByPrefix()` or `language_get_by_prefix()` when a consumer needs a subset of translations:

```php
$translations = language_get_by_prefix('app.pages', 'en');
```

The prefix is normalized through `Language::normalizePrefix()` / `language_normalize_prefix()`, so `app.pages` and `app.pages.` both return keys such as `app.pages.users.profile`. Full keys are preserved in the returned array.

The protected system API exposes the same filtering over HTTP:

```text
GET /api-system/i18n?prefix=app.pages&lang=en
X-System-Token: <SYSTEM_TOKEN>
```

`SYSTEM_TOKEN` must be configured in `.env`; an empty token disables the endpoint. `System\Middlewares\SystemI18nAuth` validates `X-System-Token` or `Authorization: Bearer <token>` before the request reaches `System\Controllers\I18n`. The response includes `lang`, normalized `prefix`, and `translations`.

Vue pages rendered through `view_render_vue()` can request these translations by passing i18n prefixes as the fourth renderer argument:

```php
return response_html(view_render_vue(
    'users/Profile',
    ['user' => $user],
    null,
    ['app.pages.users', 'app.back']
));
```

`resources/vue/main.js` fetches each prefix from `/api-system/i18n`, sends `SYSTEM_TOKEN` in the `X-System-Token` header, passes through the system i18n auth middleware, merges the translation maps, and provides `t(key)` to Vue components. Missing keys return the key itself so Vue can mount safely when i18n is disabled or unavailable.

Security note: direct browser fetches expose `SYSTEM_TOKEN` in Vue boot data. Treat it as protection for framework utility endpoints, not private user data.

## Interface Text

When creating or changing visible text:

- prefer app-owned keys in `app/languages/*` and framework-owned keys in `system/languages/*`;
- use `app.*` for application text and `system.*` for framework, documentation, template, validation, and core error text;
- keep subdirectory prefixes consistent with the folder;
- use `lg()` in views;
- avoid loose strings when the text is part of the public interface.
