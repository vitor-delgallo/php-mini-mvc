<?php

// Loads Composer's autoloader (includes all dependencies)
require_once '../vendor/autoload.php';

use MiladRahimi\PhpRouter\Exceptions\RouteNotFoundException;
use System\Config\Environment AS ConfigEnvironment;
use System\Config\Database AS ConfigDatabase;
use System\Config\Globals;
use System\Core\PHPAutoload;
use System\Core\RouterLoader;
use System\Core\Path;
use System\Core\Response;
use System\Core\Database;
use System\Core\Language;
use System\Session\NULLHandler;

// Includes custom error handlers (e.g. set_error_handler, shutdown_function)
include_once Path::systemIncludes() . '/error_handlers.php';

$response = null;
$apiPrefix = "/api";
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
    PHPAutoload::from(Path::systemHelpers());

    // Autoloads all application-specific helper files
    PHPAutoload::from(Path::appHelpers());

    // Detect which route file to load
    $uri = $_SERVER['REQUEST_URI'] ?? '/';
    $base = Globals::env('BASE_PATH') ?? '';
    $cleanUri = str_replace($base, '', $uri);
    $useSession = true;

    // If the route starts with api prefix, disable session
    if (preg_match('#^' . preg_quote($apiPrefix) . '(/|$)#', $cleanUri)) {
        $useSession = false;
    }

    // Init session only if needed
    if ($useSession) {
        // Initializes session handling based on configured driver (files, db, or none)
        include_once Path::systemIncludes() . '/session_handlers.php';
    } else {
        ini_set('session.use_cookies', 0);
        ini_set('session.use_trans_sid', 0);
        session_set_save_handler((new NULLHandler()), true);
    }

    // Establish a database connection if a valid driver is configured
    if(!ConfigDatabase::isNone()) {
        Database::connect();
    }

    // Starts all bootable classes
    PHPAutoload::boot();

    // Loads and registers all application routes
    RouterLoader::load('web');
    RouterLoader::loadWithPrefix($apiPrefix, 'api');
    RouterLoader::dispatch();
} catch (RouteNotFoundException $e) {
    // Handles route not found (404) with a basic HTML response
    $response = Response::html('<h1>' . Language::get("system.http.404.title") . '</h1>', 404);
} catch (\Throwable $e) {
    // Handles any uncaught exception (500 Internal Server Error)
    $response = Response::html('<h1>' . Language::get("system.http.500.title") . '</h1>', 500);

    // In non-production environments, show detailed error information
    if (!ConfigEnvironment::isProduction()) {
        $response = Response::html('<h1>' . Language::get("system.http.500.title") . '</h1><pre>'. htmlspecialchars($e) . '</pre>', 500);

        // Also log the error to a daily log file
        $logName = date('Y-m-d') . ".log";
        $log = "[" . date('Y-m-d H:i:s') . "] [EXCEPTION] " . $e->getMessage() . " in " .
            $e->getFile() . ":" . $e->getLine() . "\n";
        $path = Path::storageLogs() . '/' . $logName;

        file_put_contents($path, $log, FILE_APPEND);
    }
}

// TODO: Create DB ORM class (???)