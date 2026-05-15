# Helper And System Class Reference

Internal helpers are loaded before routes and are the recommended short API for views, controllers, and middlewares.

Use this file as the index. The detailed references below were checked against the current `system/` source files and list both static class usage and procedural helper usage when a helper exists.

## Helper-Backed Classes

| Class | Namespace | Helpers | Reference |
| --- | --- | --- | --- |
| Database config | `System\Config\Database` | `database_driver`, `database_is`, `database_is_mysql`, `database_is_postgres`, `database_is_none` | [01-system-config-database.md](helpers/01-system-config-database.md) |
| Database core | `System\Core\Database` | `database_connect`, `database_select`, `database_select_row`, `database_statement`, transactions, disconnect | [02-system-core-database.md](helpers/02-system-core-database.md) |
| Environment | `System\Config\Environment` | `environment_type`, `environment_is`, `environment_is_production`, `environment_is_development`, `environment_is_testing` | [03-system-config-environment.md](helpers/03-system-config-environment.md) |
| Globals | `System\Config\Globals` | `globals_get`, `globals_add`, `globals_merge`, `globals_forget`, `globals_reset`, `globals_env`, app and system request helpers | [04-system-config-globals.md](helpers/04-system-config-globals.md) |
| Session config | `System\Config\Session` | `session_driver`, `session_is`, `session_is_files`, `session_is_db`, `session_is_none` | [05-system-config-session.md](helpers/05-system-config-session.md) |
| Form validator | `System\Core\FormValidator` | `form_validator`, `form_validator_register_rule` | [06-system-core-form-validator.md](helpers/06-system-core-form-validator.md) |
| Language | `System\Core\Language` | `language_get`, `language_get_by_prefix`, `language_normalize_prefix`, `lg`, `language_load`, `ld`, `language_detect`, `language_current`, `language_default` | [07-system-core-language.md](helpers/07-system-core-language.md) |
| Path | `System\Core\Path` | `path_*`, `site_url` | [08-system-core-path.md](helpers/08-system-core-path.md) |
| PHP autoload | `System\Core\PHPAutoload` | `php_autoload_from`, `php_autoload_boot` | [12-system-core-phpautoload.md](helpers/12-system-core-phpautoload.md) |
| Response | `System\Core\Response` | `response_redirect`, `response_html`, `response_text`, `response_json`, `response_xml`, `response_file` | [09-system-core-response.md](helpers/09-system-core-response.md) |
| Router loader | `System\Core\RouterLoader` | `router_loader_load`, `router_loader_load_with_prefix`, `router_loader_load_system`, `router_loader_load_system_with_prefix`, `router_loader_dispatch` | [13-system-core-routerloader.md](helpers/13-system-core-routerloader.md) |
| Session core | `System\Core\Session` | `session_start_safe`, `session_has`, `session_get`, `session_set`, `session_set_many`, `session_forget`, `session_clear`, `session_save`, `session_destroy_safe`, `session_regenerate` | [10-system-core-session.md](helpers/10-system-core-session.md) |
| View | `System\Core\View` | `view_share`, `view_share_many`, `view_forget`, `view_forget_many`, `view_clear`, `view_set_template`, `view_get_template`, `view_render_page`, `view_render_system_page`, `view_render_html`, `view_render_vue`, `view_globals` | [11-system-core-view.md](helpers/11-system-core-view.md) |

## System Classes Without Procedural Helpers

| Class | Namespace | Reference |
| --- | --- | --- |
| Database session handler | `System\Session\DBHandler` | [14-system-session-dbhandler.md](helpers/14-system-session-dbhandler.md) |
| Null session handler | `System\Session\NULLHandler` | [15-system-session-nullhandler.md](helpers/15-system-session-nullhandler.md) |
| Bootable contract | `System\Interfaces\IBootable` | [16-system-interfaces-ibootable.md](helpers/16-system-interfaces-ibootable.md) |

## Quick Decisions

| Need | Use |
| --- | --- |
| Render a page | `view_render_page()` + `response_html()` |
| Render a system page | `view_render_system_page()` + `response_html()` |
| Render an optional Vue page | `view_render_vue()` + `response_html()` |
| Return JSON | `response_json()` |
| Redirect | `response_redirect()` |
| Generate an absolute URL | `site_url()` |
| Generate an asset path | `path_base_public()` |
| Fetch a translation | `lg()` |
| Normalize a translation prefix | `language_normalize_prefix()` |
| Load a route file | `router_loader_load()` / `router_loader_load_with_prefix()` |
| Query multiple rows | `database_select()` |
| Query one row | `database_select_row()` |
| Run insert/update/delete | `database_statement()` |
| Validate a form | `form_validator()` |
| Read web session data | `session_get()` / `session_has()` |
| Share a global variable with views | `view_share()` |
| Lightweight global initialization | Class in `app/Bootable` implementing `IBootable` |

## Usage Guidance

- Prefer helpers in views, controllers, and middlewares when they already exist.
- Use static class methods when no helper exists or when the class method is clearer for bootstrap/internal code.
- Avoid duplicating a helper in the app when the same behavior already exists in `system/helpers`.
- Before adding a dependency or new helper, check whether the existing helpers and system classes already solve the case.
