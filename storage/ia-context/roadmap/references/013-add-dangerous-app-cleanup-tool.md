# Add Dangerous App Cleanup Tool

## Goal

Add a system-home action that can remove example/application MVC files and generated project files, leaving a clean app skeleton with `.gitkeep` files where needed.

## Required Context

- Read this plan first.
- Then read `storage/ia-context/mvc.md` for the project conventions needed to implement the task.
- Also inspect:
  - `app/views/pages/home.php` or `system/views/pages/home.php`
  - `app/routes/web.php`
  - `app/routes/api.php`
  - `app/Bootable/`
  - `app/Controllers/`
  - `app/Middlewares/`
  - `app/Models/`
  - `app/views/`
  - `system/Core/Response.php`
  - `storage/ia-context/roadmap/references/008-move-documentation-home-to-system-route.md`

## Destructive Behavior

The cleanup must remove application example files and leave the application area ready for a new project.

Clean these app folders:

```text
app/Bootable/
app/Controllers/
app/Middlewares/
app/Models/
app/helpers/
app/views/pages/
app/views/templates/
```

Each cleaned directory must contain only:

```text
.gitkeep
```

Also clean these optional project folders when they exist:

```text
resources/vue/pages/
languages/app/
app/languages/
storage/logs/
storage/sessions/
public/assets/css/
public/assets/js/
public/assets/libs/
public/assets/img/
```

Rules for these optional folders:

- `resources/vue/pages/` must receive a `.gitkeep` after cleanup.
- app language folders must receive a `.gitkeep` after cleanup.
- `storage/logs/` and `storage/sessions/` should be emptied but the directories must remain.
- public asset subfolders should be emptied but the directories must remain.

Route behavior after cleanup:

- `app/routes/web.php` must keep only the root redirect to the system documentation home.
- `app/routes/api.php` must be emptied to a safe no-route file or left with only a minimal comment.

The app root route must redirect with the static response class, not the helper:

```php
return \System\Core\Response::redirect('/web-system');
```

## UI Plan

Add a button to the documentation home:

```text
Remove and Clean MVC
```

Use SweetAlert for the confirmation modal.

The modal must:

- clearly say the action is irreversible;
- say it will clean the entire `app/` MVC area;
- list every folder that will be cleaned, including app MVC folders, Vue pages, app language files, logs, sessions, and public asset subfolders;
- explain that app routes will be reset, with `app/routes/web.php` keeping only the redirect to `/web-system` and `app/routes/api.php` being cleared;
- disable the confirm button for 10 seconds;
- show a countdown on the confirm button;
- only enable confirmation after the countdown finishes.

## Backend Plan

1. Create a system-only POST endpoint for the cleanup action.
2. Prefer placing the action in a system controller, for example `System\Controllers\Maintenance`.
3. Protect the endpoint from accidental external use:
   - require POST;
   - require a CSRF-style nonce generated into the home page;
   - if `SYSTEM_TOKEN` exists, require it too or use it to sign the nonce.
4. Resolve every target path from static `Path` methods or fixed project-root paths.
5. Verify each resolved path stays inside its expected base directory before deleting.
6. Delete only files and folders inside the explicit target list.
7. Recreate required directories and `.gitkeep` files.
8. Rewrite `app/routes/web.php` to the redirect-only route using `\System\Core\Response::redirect('/web-system')`.
9. Rewrite `app/routes/api.php` to a minimal safe file.
10. Return JSON indicating success or failure.

## Safety Rules

- Never delete `app/routes/` itself.
- Never delete `system/`, `public/`, `storage/`, vendor files, or project root files.
- Only delete contents inside the allowed `storage/logs/`, `storage/sessions/`, and `public/assets/*` subfolders.
- Do not delete the `storage/logs/`, `storage/sessions/`, `public/assets/css/`, `public/assets/js/`, `public/assets/libs/`, or `public/assets/img/` directories themselves.
- Never follow user-provided paths for deletion.
- Do not use broad process or shell cleanup commands.
- Keep the cleanup target list explicit in PHP code.
- Log or return the list of cleaned targets.

## Documentation Updates

Update the home documentation and relevant MVC docs to explain:

- what the cleanup button does;
- that it is irreversible;
- exactly which folders are cleaned;
- that app routes are reset;
- that it prepares the app folder and common generated folders for a fresh project;
- that system documentation remains available at `/web-system`.

## Acceptance Criteria

- The home page shows a SweetAlert-powered cleanup button.
- The confirm button is locked for 10 seconds with a visible countdown.
- The cleanup endpoint is system-owned and protected.
- The app MVC folders are cleaned and contain `.gitkeep`.
- `resources/vue/pages/` and app language folders are cleaned with `.gitkeep` when they exist.
- `storage/logs/`, `storage/sessions/`, and public asset subfolders are emptied without deleting their parent directories.
- `app/routes/web.php` keeps only the static-class redirect to `/web-system`.
- `app/routes/api.php` is reset to a minimal safe file.
- The action does not touch system files, public files outside allowed asset subfolders, storage files outside logs/sessions, vendor, or root project files.
- Documentation clearly warns that the action is irreversible.
