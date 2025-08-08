<?php
namespace System\Core;

use \RecursiveIteratorIterator;
use \RecursiveDirectoryIterator;
use \RegexIterator;
use \InvalidArgumentException;
use \System\Interfaces\IBootable;

/**
 * Autoload utility for loading PHP file dynamically.
 *
 * Recursively scans a given directory and includes all `.php` files found within it.
 * Useful for loading helpers, components, or additional logic during bootstrap.
 */
class PHPAutoload {
    private static function getFiles(string $directory): RegexIterator {
        if (!is_dir($directory)) {
            throw new InvalidArgumentException(Language::get("system.invalid.directory.info") . $directory);
        }

        // Use RecursiveDirectoryIterator to scan all files/subdirectories
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory)
        );

        // Filter only .php files
        return new RegexIterator($iterator, '/\.php$/i');
    }

    /**
     * Recursively loads all `.php` files from the specified directory.
     *
     * @param string $directory Absolute path to the directory.
     * @throws \InvalidArgumentException If the given path is not a valid directory.
     * @return void
     */
    public static function from(string $directory): void {
        // Require each PHP file found
        foreach (self::getFiles($directory) AS $file) {
            require_once $file;
        }
    }

    /**
     * Loads all `.php` files from a directory and boots classes that implement Bootable.
     *
     * The class must match the filename (PSR-4 assumed) and implement System\Interfaces\IBootable.
     *
     * @return void
     */
    public static function boot(): void {
        foreach (self::getFiles(Path::appBootable()) AS $file) {
            $relativePath = str_replace([Path::appBootable(), '/', '.php'], ['', '\\', ''], $file->getRealPath());

            $class = trim("App\\Bootable" . $relativePath, '\\');
            if (class_exists($class) && is_subclass_of($class, IBootable::class)) {
                $class::boot();
            }
        }
    }
}