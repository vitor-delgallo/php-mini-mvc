<?php

use \System\Core\View;

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