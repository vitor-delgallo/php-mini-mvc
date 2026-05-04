<?php

use System\Config\Database AS Config;
use \System\Core\Database;

function database_driver(): string {
    return Config::env();
}

function database_is(string $env): bool {
    return Config::is($env);
}

function database_is_mysql(): bool {
    return Config::isMysql();
}

function database_is_postgres(): bool {
    return Config::isPostgres();
}

function database_is_none(): bool {
    return Config::isNone();
}

function database_connect(): PDO {
    return Database::connect();
}

function database_select(string $sql, array $params = [], ?string $key = null): array {
    return Database::select($sql, $params, $key);
}

function database_select_row(string $sql, array $params = [], ?string $key = null): mixed {
    return Database::selectRow($sql, $params, $key);
}

function database_statement(string $sql, array $params = []): bool {
    return Database::statement($sql, $params);
}

function database_get_last_inserted_id(): string|false {
    return Database::getLastInsertedID();
}

function database_is_in_transaction(): bool {
    return Database::isInTransaction();
}

function database_start_transaction(): bool {
    return Database::startTransaction();
}

function database_commit_transaction(): bool {
    return Database::commitTransaction();
}

function database_rollback_transaction(): bool {
    return Database::rollbackTransaction();
}

function database_disconnect(): void {
    Database::disconnect();
}
