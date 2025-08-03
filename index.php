<?php

// Loads Composer's autoloader (includes all dependencies)
require 'vendor/autoload.php';

use Laminas\HttpHandlerRunner\Emitter\SapiEmitter;
use League\Route\Http\Exception\NotFoundException;
use System\Config\Environment AS ConfigEnvironment;
use System\Config\Database AS ConfigDatabase;
use System\Config\Globals;
use System\Core\Autoload;
use System\Core\Path;
use System\Core\Response;
use System\Core\Database;
use System\Core\Language;

// Includes custom error handlers (e.g. set_error_handler, shutdown_function)
include Path::systemIncludes() . '/error_handlers.php';

// Initializes the response variable
$response = null;
try {
    // Loads .env environment variables into memory
    Globals::loadEnv();

    // Configures error visibility based on the environment
    if (ConfigEnvironment::isProduction()) {
        ini_set('display_errors', '0');
        ini_set('display_startup_errors', '0');
        error_reporting(0);
    } else {
        ini_set('display_errors', '1');
        ini_set('display_startup_errors', '1');
        error_reporting(E_ALL);
    }

    // Autoloads all system helper files
    Autoload::from(Path::systemHelpers());

    // Autoloads all application-specific helper files
    Autoload::from(Path::appHelpers());

    // Initializes session handling based on configured driver (files, db, or none)
    include Path::systemIncludes() . '/session_handlers.php';

    // Establish a database connection if a valid driver is configured
    if(!ConfigDatabase::isNone()) {
        Database::connect();
    }

    // Loads and registers all application routes
    include Path::systemIncludes() . '/router_init.php';
    include Path::app() . '/routes.php';
    include Path::systemIncludes() . '/router_dispatch.php';
} catch (NotFoundException $e) {
    // Handles route not found (404) with a basic HTML response
    $response = Response::html('<h1>' . Language::get("http.404.title") . '</h1>', 404);
} catch (\Throwable $e) {
    // Handles any uncaught exception (500 Internal Server Error)
    $response = Response::html('<h1>' . Language::get("http.500.title") . '</h1>', 500);

    // In non-production environments, show detailed error information
    if (!ConfigEnvironment::isProduction()) {
        $response = Response::html('<h1>' . Language::get("http.500.title") . '</h1><pre>'. htmlspecialchars($e) . '</pre>', 500);

        // Also log the error to a daily log file
        $logName = date('Y-m-d') . ".log";
        $log = "[" . date('Y-m-d H:i:s') . "] [EXCEPTION] " . $e->getMessage() . " in " .
            $e->getFile() . ":" . $e->getLine() . "\n";
        $path = Path::storageLogs() . '/' . $logName;

        file_put_contents($path, $log, FILE_APPEND);
    }
}

// Sends the final HTTP response to the client
(new SapiEmitter())->emit($response);

// TODO: Create form validation class (???)
// TODO: Optional language per path (???)