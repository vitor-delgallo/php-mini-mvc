# Helper Reference

Internal helpers are loaded automatically before routes. They are the recommended short API for the application.

## Functions by Area

| Area | Helpers |
| --- | --- |
| Database config | `database_driver`, `database_is`, `database_is_mysql`, `database_is_postgres`, `database_is_none` |
| Database core | `database_connect`, `database_select`, `database_select_row`, `database_statement`, `database_get_last_inserted_id`, `database_is_in_transaction`, `database_start_transaction`, `database_commit_transaction`, `database_rollback_transaction`, `database_disconnect` |
| Environment | `environment_type`, `environment_is`, `environment_is_production`, `environment_is_development`, `environment_is_testing` |
| Form | `form_validator`, `form_validator_register_rule` |
| Globals | `globals_get`, `globals_add`, `globals_merge`, `globals_forget`, `globals_forget_many`, `globals_reset`, `globals_load_env`, `globals_env`, `globals_get_api_prefix`, `globals_is_api_request` |
| Languages | `lg`, `language_get`, `language_load`, `ld`, `language_detect`, `language_current`, `language_default` |
| Path | `path_root`, `path_app`, `path_app_bootable`, `path_app_helpers`, `path_app_routes`, `path_app_middlewares`, `path_app_controllers`, `path_app_models`, `path_app_views`, `path_app_views_pages`, `path_app_views_templates`, `path_system`, `path_system_interfaces`, `path_system_helpers`, `path_system_includes`, `path_public`, `path_storage`, `path_storage_sessions`, `path_storage_logs`, `path_languages`, `path_base`, `path_base_public`, `site_url` |
| Response | `response_redirect`, `response_html`, `response_text`, `response_json`, `response_xml`, `response_file` |
| Session | `session_start_safe`, `session_has`, `session_get`, `session_set`, `session_set_many`, `session_forget`, `session_clear`, `session_save`, `session_destroy_safe`, `session_regenerate`, `session_driver`, `session_is`, `session_is_files`, `session_is_db`, `session_is_none` |
| View | `view_share`, `view_share_many`, `view_forget`, `view_forget_many`, `view_set_template`, `view_get_template`, `view_render_page`, `view_render_html`, `view_globals` |

## Quick Decisions

| Need | Use |
| --- | --- |
| Render a page | `view_render_page()` + `response_html()` |
| Return JSON | `response_json()` |
| Redirect | `response_redirect()` |
| Generate an absolute URL | `site_url()` |
| Generate an asset path | `path_base_public()` |
| Fetch a translation | `lg()` |
| Query multiple rows | `database_select()` |
| Query one row | `database_select_row()` |
| Run insert/update/delete | `database_statement()` |
| Validate a form | `form_validator()` |
| Read web session data | `session_get()` / `session_has()` |
| Share a global variable with views | `view_share()` |
| Lightweight global initialization | Class in `app/Bootable` implementing `IBootable` |

## Usage Guidance

- Prefer helpers in views, controllers, and middlewares when they already exist.
- Use core classes when you need behavior that is more specific than what the helper exposes.
- Avoid duplicating a helper in the app when the same behavior already exists in `system/helpers`.
- Before adding a dependency, check whether the helpers and core already solve the case.
