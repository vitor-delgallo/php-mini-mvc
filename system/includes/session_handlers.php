<?php

use System\Config\Session AS ConfigSession;
use System\Config\Globals;
use System\Core\Database;
use System\Core\Session;
use System\Core\Path;
use System\Session\DBHandler;

/**
 * Initializes session handling based on the configured driver.
 *
 * Supported drivers:
 * - files: Uses native PHP file-based session storage.
 * - db: Uses a custom session handler that stores sessions in the database.
 */

// If session driver is 'files', use native PHP file-based storage
if (ConfigSession::isFiles()) {
    ini_set('session.save_handler', 'files');
    ini_set('session.save_path', Path::storageSessions());
    Session::start();
} else if (ConfigSession::isDB()) {
    // If session driver is 'db', initialize custom DBHandler

    // Create a PDO connection from the application's database configuration
    $pdo = Database::connect();

    // Instantiate the session handler with optional prefix and encryption key
    $handler = new DBHandler(
        $pdo,
        Globals::get('SESSION_PREFIX'), // opcional
        Globals::get('SESSION_ENCRYPT_KEY') // opcional (32 caracteres)
    );

    // Register the custom session handler
    session_set_save_handler($handler, true);

    // Start the session
    Session::start();
}