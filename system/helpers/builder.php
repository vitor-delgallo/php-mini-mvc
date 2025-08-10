<?php

/**
 * ==========================================================
 * 🔧 Query Builder Helper
 * ==========================================================
 *
 * This helper exposes a simple way to obtain a reusable instance
 * of the {@see System\Database\Builder} class. The builder
 * offers a fluent interface for composing SQL queries and is
 * designed to integrate seamlessly with the existing Database
 * layer. By using the global function {@see database_query_builder()} you can
 * quickly start building a query without having to repeatedly
 * instantiate the builder yourself.
 *
 * The helper maintains a singleton instance internally. Each call
 * to {@see database_query_builder()} will return this instance, optionally resetting
 * its internal state before returning it. This allows developers
 * to reuse the same builder object across multiple calls while
 * ensuring that previous query state does not leak into subsequent
 * queries unless explicitly desired.
 */

use System\Database\Builder;

/**
 * Returns a fluent query builder. If `$reset` is set to false the caller can
 * continue building off the existing state.
 *
 * Example:
 * ```php
 * // Simple select using the helper
 * $users = database_query_builder()->table('users')->select('id', 'name')->get();
 *
 * // Insert a new record
 * $inserted = database_query_builder()->table('users')->insert([
 *     'name'  => 'John',
 *     'email' => 'john@example.com',
 * ]);
 * ```
 *
 * @param bool $reset Whether to reset the builder state before returning
 * @return Builder
 */
function database_query_builder(bool $reset = false): Builder {
    static $builder = null;

    if($reset || $builder === null) {
        $builder = new Builder();
    }

    return $builder;
}

function qb(bool $reset = false): Builder {
    return database_query_builder($reset);
}