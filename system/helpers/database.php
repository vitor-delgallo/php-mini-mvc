<?php

use System\Config\Database AS Config;
use \System\Core\Database;

function database_driver(string $connection = 'default'): string {
    return Config::env($connection);
}

function database_is(string $env, string $connection = 'default'): bool {
    return Config::is($env, $connection);
}

function database_is_mysql(string $connection = 'default'): bool {
    return Config::isMysql($connection);
}

function database_is_postgres(string $connection = 'default'): bool {
    return Config::isPostgres($connection);
}

function database_is_none(string $connection = 'default'): bool {
    return Config::isNone($connection);
}

function database_config_permitted_drivers(): array {
    return Config::permittedDrivers();
}

function database_config_normalize_connection_name(?string $connection = null): string {
    return Config::normalizeConnectionName($connection);
}

function database_config_default_port(string $driver): ?int {
    return Config::defaultPort($driver);
}

function database_config_connection(string $connection = 'default'): ?array {
    return Config::connection($connection);
}

function database_config_connections(): array {
    return Config::connections();
}

function database_config_configure(string $connection, array $config): void {
    Config::configure($connection, $config);
}

function database_config_forget_connection(string $connection): void {
    Config::forgetConnection($connection);
}

function database_connect(string $connection = 'default'): PDO {
    return Database::connect($connection);
}

function database_configure(string $connection, array $config): void {
    Database::configure($connection, $config);
}

function database_forget_connection(string $connection): void {
    Database::forgetConnection($connection);
}

function database_has_connection(string $connection): bool {
    return Database::hasConnection($connection);
}

function database_connection_names(): array {
    return Database::connectionNames();
}

function database_select(string $sql, array $params = [], ?string $key = null, string $connection = 'default'): array {
    return Database::select($sql, $params, $key, $connection);
}

function database_select_row(string $sql, array $params = [], ?string $key = null, string $connection = 'default'): mixed {
    return Database::selectRow($sql, $params, $key, $connection);
}

function database_statement(string $sql, array $params = [], string $connection = 'default'): bool {
    return Database::statement($sql, $params, $connection);
}

function database_get_last_inserted_id(string $connection = 'default'): string|false {
    return Database::getLastInsertedID($connection);
}

function database_is_in_transaction(string $connection = 'default'): bool {
    return Database::isInTransaction($connection);
}

function database_start_transaction(string $connection = 'default'): bool {
    return Database::startTransaction($connection);
}

function database_commit_transaction(string $connection = 'default'): bool {
    return Database::commitTransaction($connection);
}

function database_rollback_transaction(string $connection = 'default'): bool {
    return Database::rollbackTransaction($connection);
}

function database_disconnect(?string $connection = null): void {
    Database::disconnect($connection);
}
