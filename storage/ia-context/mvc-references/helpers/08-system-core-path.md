# System\Core\Path

Source: `system/Core/Path.php`  
Helper source: `system/helpers/path.php`  
Namespace: `System\Core`

Resolves project filesystem paths, normalized base paths, public asset paths, and absolute site URLs.

## Static Usage

```php
use System\Core\Path;

$viewPath = Path::appViewsPages();
$url = Path::siteURL('users/1');
```

## Helper Usage

```php
$viewPath = path_app_views_pages();
$url = site_url('users/1');
```

## Method And Helper Signatures

| Static method | Helper | Accepts | Returns |
| --- | --- | --- | --- |
| `Path::root(): string` | `path_root(): string` | No arguments. | Project root directory. |
| `Path::app(): string` | `path_app(): string` | No arguments. | `app` directory. |
| `Path::appBootable(): string` | `path_app_bootable(): string` | No arguments. | `app/Bootable` directory. |
| `Path::appHelpers(): string` | `path_app_helpers(): string` | No arguments. | `app/helpers` directory. |
| `Path::appRoutes(): string` | `path_app_routes(): string` | No arguments. | `app/routes` directory. |
| `Path::appMiddlewares(): string` | `path_app_middlewares(): string` | No arguments. | `app/Middlewares` directory. |
| `Path::appControllers(): string` | `path_app_controllers(): string` | No arguments. | `app/Controllers` directory. |
| `Path::appModels(): string` | `path_app_models(): string` | No arguments. | `app/Models` directory. |
| `Path::appViews(): string` | `path_app_views(): string` | No arguments. | `app/views` directory. |
| `Path::appViewsPages(): string` | `path_app_views_pages(): string` | No arguments. | `app/views/pages` directory. |
| `Path::appViewsTemplates(): string` | `path_app_views_templates(): string` | No arguments. | `app/views/templates` directory. |
| `Path::system(): string` | `path_system(): string` | No arguments. | `system` directory. |
| `Path::systemInterfaces(): string` | `path_system_interfaces(): string` | No arguments. | `system/Interfaces` directory. |
| `Path::systemHelpers(): string` | `path_system_helpers(): string` | No arguments. | `system/helpers` directory. |
| `Path::systemIncludes(): string` | `path_system_includes(): string` | No arguments. | `system/includes` directory. |
| `Path::public(): string` | `path_public(): string` | No arguments. | `public` directory. |
| `Path::storage(): string` | `path_storage(): string` | No arguments. | `storage` directory. |
| `Path::storageSessions(): string` | `path_storage_sessions(): string` | No arguments. | `storage/sessions` directory. |
| `Path::storageLogs(): string` | `path_storage_logs(): string` | No arguments. | `storage/logs` directory. |
| `Path::languages(): string` | `path_languages(): string` | No arguments. | `languages` directory. |
| `Path::basePath(): string` | `path_base(): string` | No arguments. Reads `BASE_PATH`. | Normalized base path such as `/php-mini-mvc` or empty string. |
| `Path::basePathPublic(): string` | `path_base_public(): string` | No arguments. | Base path plus `/public`. |
| `Path::siteURL(?string $final = null): string` | `site_url(?string $final = null): string` | Optional relative URL suffix. | Absolute URL using current host, protocol, and `BASE_PATH`. |

## Notes

- Filesystem methods return Windows-style paths in this checkout because the source concatenates with `\\`.
- URL methods normalize to forward slashes.
- Use `path_base_public()` for asset URLs when the app runs from a subdirectory.
