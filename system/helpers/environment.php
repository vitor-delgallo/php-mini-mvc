<?php

use System\Config\Environment;

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