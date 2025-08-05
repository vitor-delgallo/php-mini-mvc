<?php
namespace System\Core;

/**
 * Autoload utility for loading PHP files dynamically.
 *
 * Recursively scans a given directory and includes all `.php` files found within it.
 * Useful for loading helpers, components, or additional logic during bootstrap.
 */
class Autoload {
    /**
     * Recursively loads all `.php` files from the specified directory.
     *
     * @param string $directory Absolute path to the directory.
     * @throws \InvalidArgumentException If the given path is not a valid directory.
     * @return void
     */
    public static function from(string $directory): void {
        if (!is_dir($directory)) {
            throw new \InvalidArgumentException(Language::get("system.invalid.directory.info") . $directory);
        }

        // Use RecursiveDirectoryIterator to scan all files/subdirectories
        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory)
        );

        // Filter only .php files
        $phpFiles = new \RegexIterator($iterator, '/\.php$/i');

        // Require each PHP file found
        foreach ($phpFiles AS $file) {
            require_once $file;
        }
    }
}