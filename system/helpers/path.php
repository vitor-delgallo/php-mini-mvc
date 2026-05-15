<?php

use \System\Core\Path;

function path_root(): string {
    return Path::root();
}

function path_app(): string {
    return Path::app();
}

function path_app_bootable(): string {
    return Path::appBootable();
}

function path_app_helpers(): string {
    return Path::appHelpers();
}

function path_app_languages(): string {
    return Path::appLanguages();
}

function path_app_routes(): string {
    return Path::appRoutes();
}

function path_app_middlewares(): string {
    return Path::appMiddlewares();
}

function path_app_controllers(): string {
    return Path::appControllers();
}

function path_app_models(): string {
    return Path::appModels();
}

function path_app_views(): string {
    return Path::appViews();
}

function path_app_views_pages(): string {
    return Path::appViewsPages();
}

function path_app_views_templates(): string {
    return Path::appViewsTemplates();
}

function path_system(): string {
    return Path::system();
}

function path_system_interfaces(): string {
    return Path::systemInterfaces();
}

function path_system_helpers(): string {
    return Path::systemHelpers();
}

function path_system_languages(): string {
    return Path::systemLanguages();
}

function path_system_routes(): string {
    return Path::systemRoutes();
}

function path_system_middlewares(): string {
    return Path::systemMiddlewares();
}

function path_system_controllers(): string {
    return Path::systemControllers();
}

function path_system_models(): string {
    return Path::systemModels();
}

function path_system_views(): string {
    return Path::systemViews();
}

function path_system_views_pages(): string {
    return Path::systemViewsPages();
}

function path_system_views_templates(): string {
    return Path::systemViewsTemplates();
}

function path_system_includes(): string {
    return Path::systemIncludes();
}

function path_public(): string {
    return Path::public();
}

function path_storage(): string {
    return Path::storage();
}

function path_storage_sessions(): string {
    return Path::storageSessions();
}

function path_storage_logs(): string {
    return Path::storageLogs();
}

function path_languages(): string {
    return Path::languages();
}

function path_base(): string {
    return Path::basePath();
}

function path_base_public(): string {
    return Path::basePathPublic();
}

function site_url(?string $final = null): string {
    return Path::siteURL($final);
}
