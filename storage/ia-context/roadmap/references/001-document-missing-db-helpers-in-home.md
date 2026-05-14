# Document Missing DB Helpers in Home

## Goal

Add the missing database helper documentation to the home view so it documents all DB helpers exposed by `system/helpers/database.php`.

## Required Context

- Read this plan first.
- Then read `storage/ia-context/mvc.md` for the project conventions needed to implement the task.
- Also inspect:
  - `app/views/pages/home.php` or `system/views/pages/home.php`
  - `system/helpers/database.php`
  - `system/Core/Database.php`
  - `storage/ia-context/mvc-references/helpers/02-system-core-database.md`

## Current Gap

`home.php` already documents several database helpers through the `System\Core\Database` section:

- `database_connect()`
- `database_statement()`
- `database_select()`
- `database_select_row()`
- `database_disconnect()`

The following helpers exist in `system/helpers/database.php` but are missing from the home documentation:

- `database_get_last_inserted_id()`
- `database_is_in_transaction()`
- `database_start_transaction()`
- `database_commit_transaction()`
- `database_rollback_transaction()`

## Implementation Plan

1. Update the `System\Core\Database` entry in the home view, whether it is in `app/views/pages/home.php` or `system/views/pages/home.php`.
2. Add one documentation item for each missing static method/helper pair:
   - `getLastInsertedID()` / `database_get_last_inserted_id()`
   - `isInTransaction()` / `database_is_in_transaction()`
   - `startTransaction()` / `database_start_transaction()`
   - `commitTransaction()` / `database_commit_transaction()`
   - `rollbackTransaction()` / `database_rollback_transaction()`
3. Keep each item consistent with the existing documentation array shape:
   - `name`
   - `code`
   - `comment`
   - `alt`
   - `desc`
4. Add or update translation keys in both documentation language files:
   - `languages/doc/en.json`
   - `languages/doc/pt-br.json`
5. Use short, practical examples that show when each helper should be used.
6. Keep SQL examples parameterized. Do not show user input concatenated into SQL.
7. Confirm the home page still renders after the array and translation changes.

## Suggested Documentation Notes

- `getLastInsertedID()` should explain that it wraps PDO `lastInsertId()` and may return `false`.
- `isInTransaction()` should explain that it checks the framework transaction level.
- `startTransaction()` should mention that nested calls use savepoints.
- `commitTransaction()` should mention that it commits or releases the latest savepoint.
- `rollbackTransaction()` should mention that it rolls back or rolls back to the latest savepoint.

## Acceptance Criteria

- Every helper in `system/helpers/database.php` has a matching home documentation entry or is already covered by the config database section.
- The `System\Core\Database` section includes the missing transaction and last-insert helper entries.
- English and Portuguese language files contain all new translation keys used by `home.php`.
- The home page does not output missing translation keys for the new entries.
- No unrelated documentation sections are rewritten.
