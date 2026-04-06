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
try {
    // Loads .env environment variables into memory
    Globals::loadEnv();
    $isApiRequest = Globals::isApiRequest();

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

    // Autoloads application-specific helper files
    // APP_HELPERS_AUTOLOAD=true loads all helpers (default behavior)
    // APP_HELPERS_AUTOLOAD=['helper_a','helper_b.php'] loads only selected helpers
    $appHelpersAutoload = Globals::env('APP_HELPERS_AUTOLOAD') ?? 'true';
    $appHelpersAutoloadNormalized = strtolower(trim((string) $appHelpersAutoload));

    if (in_array($appHelpersAutoloadNormalized, ['true', '1', 'all', '*'], true)) {
        PHPAutoload::from(Path::appHelpers());
    } else {
        $helpersRaw = trim((string) $appHelpersAutoload);
        $helpersToLoad = [];

        // Supports: ['my_helper','other_helper.php'] or ["my_helper"]
        if (str_starts_with($helpersRaw, '[') && str_ends_with($helpersRaw, ']')) {
            $helpersRaw = trim($helpersRaw, "[] \t\n\r\0\x0B");
            if (!empty($helpersRaw)) {
                $helpersToLoad = preg_split('/\s*,\s*/', $helpersRaw) ?: [];
            }
        } elseif (!empty($helpersRaw)) {
            // Supports single helper value
            $helpersToLoad = [$helpersRaw];
        }

        foreach ($helpersToLoad as $helper) {
            $helper = trim((string) $helper);
            $helper = trim($helper, "\"'");
            if (empty($helper)) {
                continue;
            }

            if (!str_ends_with(strtolower($helper), '.php')) {
                $helper .= '.php';
            }

            $helperPath = Path::appHelpers() . '/' . $helper;
            if (is_file($helperPath)) {
                include_once $helperPath;
            }
        }
    }

    // Init session only if needed
    if (!$isApiRequest) {
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
    if ($isApiRequest) {
        RouterLoader::loadWithPrefix(Globals::getApiPrefix(), 'api');
    } else {
        RouterLoader::load('web');
    }
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

// If a response object was generated in the exception handler, emit it to the client
if ($response instanceof \Psr\Http\Message\ResponseInterface) {
    http_response_code($response->getStatusCode());
    foreach ($response->getHeaders() as $name => $values) {
        foreach ($values as $value) {
            header("{$name}: {$value}", false);
        }
    }

    // Output the response body
    echo $response->getBody();
    exit;
}
