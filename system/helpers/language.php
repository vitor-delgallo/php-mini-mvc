<?php

use \System\Core\Language;

function language_get(?string $key = null, array $replacements = null, ?string $lang = null): string|array|null {
    return Language::get($key, $replacements, $lang);
}

function lg(?string $key = null, array $replacements = null, ?string $lang = null): string|array|null {
    return language_get($key, $replacements, $lang);
}

function language_load(?string $lang = null): void {
    Language::load($lang);
}

function ld(?string $lang = null): void {
    language_load($lang);
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