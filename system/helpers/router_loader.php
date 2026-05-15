<?php

use \System\Core\RouterLoader;

function router_loader_load(string $file): void {
    RouterLoader::load($file);
}

function router_loader_load_with_prefix(string $prefix, string $file): void {
    RouterLoader::loadWithPrefix($prefix, $file);
}

function router_loader_load_system(string $file): void {
    RouterLoader::loadSystem($file);
}

function router_loader_load_system_with_prefix(string $prefix, string $file): void {
    RouterLoader::loadSystemWithPrefix($prefix, $file);
}

function router_loader_dispatch(): void {
    RouterLoader::dispatch();
}
