<?php

/**
 * ==========================================================
 * ⚙️ Core Utility Functions
 * ==========================================================
 *
 * This file provides a collection of global helper functions
 * that simplify access to the core system components, including:
 *
 * - Autoloading
 * - Database operations (connect, select, statement, etc.)
 * - Language management (i18n)
 * - Path resolution helpers
 * - HTTP Response builders (HTML, JSON, XML, redirect)
 * - Session management (get, set, regenerate, destroy, etc.)
 * - View rendering and shared data
 *
 * These functions serve as clean and convenient shortcuts to
 * common operations within the core classes, improving developer
 * experience and reducing boilerplate throughout the application.
 */

use \System\Core\Autoload;
use \System\Core\Database;
use \System\Core\Language;
use \System\Core\Path;
use \System\Core\Response;
use \System\Core\Session;
use \System\Core\View;
use \Psr\Http\Message\ResponseInterface;

function autoload_from(string $directory): void {
    Autoload::from($directory);
}

function database_connect(): PDO {
    return Database::connect();
}

function database_select(string $sql, array $params = [], ?string $key = null): array {
    return Database::select($sql, $params, $key);
}

function database_statement(string $sql, array $params = []): bool {
    return Database::statement($sql, $params);
}

function database_select_row(string $sql, array $params = [], ?string $key = null): mixed {
    return Database::selectRow($sql, $params, $key);
}

function database_disconnect(): void {
    Database::disconnect();
}

function language_get(?string $key = null, ?string $lang = null): string|array|null {
    return Language::get($key, $lang);
}

function language_load(?string $lang = null): void {
    Language::load($lang);
}

function language_detect(): string {
    return Language::detect();
}

function language_current(): string {
    return Language::currentLang();
}

function language_default(): string {
    return Language::defaultLang();
}

function path_root(): string {
    return Path::root();
}

function path_app(): string {
    return Path::app();
}

function path_app_helpers(): string {
    return Path::appHelpers();
}

function path_controllers(): string {
    return Path::controllers();
}

function path_models(): string {
    return Path::models();
}

function path_views(): string {
    return Path::views();
}

function path_views_pages(): string {
    return Path::viewsPages();
}

function path_views_templates(): string {
    return Path::viewsTemplates();
}

function path_system(): string {
    return Path::system();
}

function path_system_helpers(): string {
    return Path::systemHelpers();
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

function path_langs(): string {
    return Path::langs();
}

function path_base(): string {
    return Path::basePath();
}

function site_url(?string $final = null): string {
    return Path::siteURL($final);
}

function response_redirect(string $uri = '', string $method = 'auto', ?int $code = null): ResponseInterface {
    return Response::redirect($uri, $method, $code);
}

function response_html(string $html, int $status = 200): ResponseInterface {
    return Response::html($html, $status);
}

function response_json(array|string $data, int $status = 200): ResponseInterface {
    return Response::json($data, $status);
}

function response_xml(string $xml, int $status = 200): ResponseInterface {
    return Response::xml($xml, $status);
}

function session_start_safe(): void {
    Session::start();
}

function session_has(string $key): bool {
    return Session::has($key);
}

function session_get(string $key, mixed $default = null): mixed {
    return Session::get($key, $default);
}

function session_set(string $key, mixed $value): void {
    Session::set($key, $value);
}

function session_set_many(array $items): void {
    Session::setMany($items);
}

function session_forget(string $key): void {
    Session::forget($key);
}

function session_clear(): void {
    Session::clear();
}

function session_save(): void {
    Session::save();
}

function session_destroy_safe(): void {
    Session::destroy();
}

function session_regenerate(bool $deleteOldSession = true): void {
    Session::regenerate($deleteOldSession);
}

function view_share(string $key, mixed $value): void {
    View::share($key, $value);
}

function view_share_many(array $items): void {
    View::shareMany($items);
}

function view_forget(string $key): void {
    View::forget($key);
}

function view_forget_many(array $keys): void {
    View::forgetMany($keys);
}

function view_set_template(?string $relativePath = null): void {
    View::setTemplate($relativePath);
}

function view_get_template(): string {
    return View::getTemplate();
}

function view_render_page(string $page, array $data = []): string {
    return View::render_page($page, $data);
}

function view_render_html(string $html, array $data = []): string {
    return View::render_html($html, $data);
}

function view_globals(): array {
    return View::getGlobals();
}