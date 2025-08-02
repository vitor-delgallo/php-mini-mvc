<?php

use System\Core\Path;
use System\Config\Environment AS ConfigEnvironment;

/**
 * Sets a custom error handler to capture PHP runtime warnings and errors.
 *
 * This handler logs error messages to a daily rotating file in the storage/logs directory.
 * In non-production environments, the error is also printed to the output.
 */
set_error_handler(function ($errno, $errStr, $errFile, $errLine) {
    // Ignore errors not included in error_reporting()
    if (!(error_reporting() & $errno)) return;

    // Format log file name (one file per day)
    $logName = date('Y-m-d') . ".log";

    // Format the log entry
    $log = "[" . date('Y-m-d H:i:s') . "] [$errno] $errStr in $errFile:$errLine\n";
    $path = Path::storageLogs() . '/' . $logName;

    // Show log output in non-production environments
    if (!ConfigEnvironment::isProduction()) {
        echo $log;
    }

    // Append the log to the file
    file_put_contents($path, $log, FILE_APPEND);
});

/**
 * Registers a shutdown function to catch fatal errors that can't be handled by set_error_handler.
 *
 * This captures and logs errors like E_ERROR, E_PARSE, etc., right before the script terminates.
 */
register_shutdown_function(function () {
    $logName = date('Y-m-d') . ".log";
    $error = error_get_last();

    // Only handle fatal errors
    if ($error && in_array($error['type'], [E_ERROR, E_PARSE, E_COMPILE_ERROR, E_CORE_ERROR])) {
        $log = "[" . date('Y-m-d H:i:s') . "] [FATAL] {$error['message']} in {$error['file']}:{$error['line']}\n";

        // Output the error if not in production
        if (!ConfigEnvironment::isProduction()) {
            echo $log;
        }

        // Append to log file
        file_put_contents(Path::storageLogs() . '/' . $logName, $log, FILE_APPEND);
    }
});