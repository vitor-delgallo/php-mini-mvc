# Move Documentation Home to System Route

## Goal

Move the framework documentation home from the app layer into the system layer and make the app root redirect to the new system documentation URL.

## Required Context

- Read this plan first.
- Then read `storage/ia-context/mvc.md` for the project conventions needed to implement the task.
- Also inspect:
  - `app/views/pages/home.php`
  - `app/views/templates/template.php`
  - `app/routes/web.php`
  - `system/Core/View.php`
  - `system/Core/Response.php`
  - `system/routes/web.php`, if it already exists
  - `storage/ia-context/mvc-references/03-mvc-layers.md`

## Desired URL

The documentation home should move to:

```text
/web-system
```

With `BASE_PATH=/php-mini-mvc`, it should be available at:

```text
/php-mini-mvc/web-system
```

The index route inside the system web routes should be `/` relative to the `/web-system` prefix.

## Required Move

Move the documentation home view and controller responsibility into `system/`.

Recommended structure:

```text
system/
  Controllers/
    Home.php
  views/
    pages/
      home.php
```

If the existing View system only supports `app/views`, extend it carefully so system views can be rendered without breaking app views.

## Implementation Plan

1. Create a system controller for the documentation home.
2. Move or copy the documentation home view into a system view location.
3. Add a system web route:

```php
$router->get('/', [\System\Controllers\Home::class, 'index']);
```

4. The controller should return a PSR-7 response, usually:

```php
return response_html(...);
```

5. Reuse the existing view/template rendering logic where possible.
6. If a system-specific render helper is needed, keep it consistent with `view_render_page()`.
7. Update `app/routes/web.php` so `/` redirects to the new documentation page:

```php
return response_redirect('/web-system');
```

8. Preserve the existing app example routes under `/admin`.
9. Ensure the redirect respects `BASE_PATH`; `response_redirect('/web-system')` should use `site_url()`.

## Template Considerations

The main template can remain PHP/HTML. Do not make Vue or Vite required for this change.

If system views use the same template, make sure the template can include a system page without hardcoding only:

```php
path_app_views_pages()
```

Possible approaches:

- pass a resolved view path to the template;
- add a system render method;
- add a view source flag.

Choose the smallest approach that preserves current app view behavior.

## Acceptance Criteria

- `/web-system` renders the documentation home.
- The app web root `/` redirects to `/web-system`.
- The documentation home view/controller are owned by `system/`.
- Existing app pages still render through the current app view flow.
- The solution works with and without `BASE_PATH`.
- No Vue/Vite dependency is introduced by this change.
