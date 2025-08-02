<?php

/**
 * ==========================================================
 * 🌐 System Configuration Helpers
 * ==========================================================
 *
 * This file provides global helper functions to simplify access
 * to system configuration classes such as:
 * - Database
 * - Session
 * - Environment
 * - Globals
 *
 * These functions serve as convenient wrappers to interact
 * with core configuration features throughout the application,
 * reducing boilerplate and improving code readability.
 */

use System\Config\Database;
use System\Config\Environment;
use System\Config\Globals;
use System\Config\Session;

function database_driver(): string {
    return Database::env();
}

function database_is(string $env): bool {
    return Database::is($env);
}

function database_is_mysql(): bool {
    return Database::isMysql();
}

function database_is_postgres(): bool {
    return Database::isPostgres();
}

function database_is_none(): bool {
    return Database::isNone();
}

function session_driver(): string {
    return Session::env();
}

function session_is(string $env): bool {
    return Session::is($env);
}

function session_is_files(): bool {
    return Session::isFiles();
}

function session_is_db(): bool {
    return Session::isDB();
}

function session_is_none(): bool {
    return Session::isNone();
}

function environment_type(): string {
    return Environment::env();
}

function environment_is(string $env): bool {
    return Environment::is($env);
}

function environment_is_production(): bool {
    return Environment::isProduction();
}

function environment_is_development(): bool {
    return Environment::isDevelopment();
}

function environment_is_testing(): bool {
    return Environment::isTesting();
}

function globals_get(?string $key = null): array|string|null {
    return Globals::get($key);
}

function globals_reset(): void {
    Globals::reset();
}

function globals_merge(array $config): void {
    Globals::merge($config);
}

function globals_add(string $key, mixed $value): void {
    Globals::add($key, $value);
}

function globals_forget(string $key): void {
    Globals::forget($key);
}

function globals_forget_many(array $config): void {
    Globals::forgetMany($config);
}

function globals_load_env(): void {
    Globals::loadEnv();
}