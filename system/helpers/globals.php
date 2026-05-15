<?php

use System\Config\Globals;

function globals_get(?string $key = null, mixed $default = null): mixed {
    return Globals::get($key, $default);
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

function globals_env(?string $key = null): array|string|null {
    return Globals::env($key);
}

function globals_get_api_prefix(): string {
    return Globals::getApiPrefix();
}

function globals_is_api_request(): bool {
    return Globals::isApiRequest();
}

function globals_get_system_web_prefix(): string {
    return Globals::getSystemWebPrefix();
}

function globals_get_system_api_prefix(): string {
    return Globals::getSystemApiPrefix();
}

function globals_is_system_web_request(): bool {
    return Globals::isSystemWebRequest();
}

function globals_is_system_api_request(): bool {
    return Globals::isSystemApiRequest();
}
