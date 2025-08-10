<?php

use \System\Core\Session;
use System\Config\Session AS Config;

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

function session_driver(): string {
    return Config::env();
}

function session_is(string $env): bool {
    return Config::is($env);
}

function session_is_files(): bool {
    return Config::isFiles();
}

function session_is_db(): bool {
    return Config::isDB();
}

function session_is_none(): bool {
    return Config::isNone();
}