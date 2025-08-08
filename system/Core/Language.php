<?php

namespace System\Core;

use System\Config\Globals;

/**
 * Language handler and translator for the application.
 *
 * Loads JSON-based translation files from the /languages directory,
 * detects or uses a specified language code (e.g., "pt-br", "en"),
 * and provides translated strings on demand.
 */
class Language {
    /**
     * Loaded translations for the current language.
     *
     * @var array<string, string>
     */
    private static array $translations = [];

    /**
     * Currently active language code (e.g., "en", "pt-br").
     *
     * @var string|null
     */
    private static ?string $langCode = null;

    /**
     * Retrieve a translated string by key, replacing dynamic parameters if needed.
     *
     * If no language is loaded yet, or a different one is requested,
     * the appropriate language will be (re)loaded.
     *
     * @param string|null $key  Translation key to retrieve (optional).
     * @param array|null  $replacements Associative array of placeholders to replace (optional).
     * @param string|null $lang Language code to force reload (optional).
     * @return string|array|null
     */
    public static function get(?string $key = null, ?array $replacements = null, ?string $lang = null): string|array|null {
        if (empty(self::$translations) || (!empty($lang) && self::currentLang() !== $lang)) {
            self::load($lang);
        }

        if (!$key) {
            return self::$translations;
        }

        $text = self::$translations[$key] ?? null;

        // Replace placeholders like {param} with actual values
        if (is_string($text) && !empty($replacements)) {
            foreach ($replacements as $k => $v) {
                $text = str_replace('{' . $k . '}', (string) $v, $text);
            }
        }

        return $text;
    }

    /**
     * Get the current language code in use.
     *
     * @return string|null
     */
    public static function currentLang(): ?string {
        return self::$langCode;
    }

    /**
     * Recursively find all files with the exact language filename (e.g., pt-br.json).
     *
     * @param string $lang Language code
     * @return array<string> List of full paths
     */
    private static function findLangFiles(string $lang): array {
        $directory = Path::languages();
        $target = "{$lang}.json";
        $matches = [];

        $iterator = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \FilesystemIterator::SKIP_DOTS)
        );

        foreach ($iterator AS $file) {
            if (
                $file->isFile() &&
                $file->getExtension() === 'json' &&
                $file->getFilename() === $target
            ) {
                $matches[] = $file->getPathname();
            }
        }

        return $matches;
    }

    /**
     * Adds a string prefix to all keys of a flat associative array.
     *
     * - If a key already starts with the prefix, it will be left unchanged.
     * - Useful for avoiding key collisions when merging multiple translation files.
     *
     * Example:
     *   prefixKeys(["title" => "Dashboard"], "admin.") returns ["admin.title" => "Dashboard"]
     *
     * @param array $arr The original key-value array.
     * @param string $prefix The prefix to prepend to each key.
     * @return array The new array with prefixed keys.
     */
    private static function prefixKeys(array $arr, string $prefix): array {
        $prefixed = [];
        foreach ($arr AS $key => $value) {
            if(str_starts_with($key, $prefix)) {
                $prefixed["{$key}"] = $value;
                continue;
            }
            $prefixed["{$prefix}{$key}"] = $value;
        }
        return $prefixed;
    }

    /**
     * Loads and merges all translation files matching the given language code.
     *
     * - Recursively scans the language directory for files named exactly "<lang>.json".
     * - Keys from files in subdirectories are automatically prefixed with the relative path (dot notation).
     *   Example: "modules/admin/pt-br.json" will prefix keys with "modules.admin.".
     * - All keys are flattened and merged into `self::$translations`.
     *
     * @param string $lang Language code (e.g., "pt-br").
     * @return bool True if any files were found and loaded, false otherwise.
     */
    private static function loadLangFiles(string $lang): bool {
        $files = self::findLangFiles($lang);
        if (empty($files)) {
            return false;
        }

        self::$translations = [];
        $baseDir = realpath(Path::languages());

        foreach ($files as $file) {
            $json = json_decode(file_get_contents($file), true);
            if (!is_array($json)) {
                continue;
            }

            $fileDir = realpath(dirname($file));
            $relativePath = trim(str_replace($baseDir, '', $fileDir), DIRECTORY_SEPARATOR);
            $prefix = $relativePath ? str_replace(DIRECTORY_SEPARATOR, '.', $relativePath) . '.' : '';

            if (!empty($prefix)) {
                $json = self::prefixKeys($json, $prefix);
            }

            self::$translations = array_merge(self::$translations, $json);
        }

        return true;
    }

    /**
     * Loads the appropriate translation files into memory.
     *
     * Enhanced behavior:
     * - Recursively searches for all JSON files with the exact name matching the language code (e.g., "pt-br.json").
     * - All matching files are loaded and merged into the translation array.
     * - Files located in subdirectories will have their keys automatically prefixed with the relative folder path.
     *   Example: a file in "admin/pt-br.json" will generate keys like "admin.title".
     * - Later files may override keys loaded earlier.
     *
     * Load priority:
     * 1. Full language code (e.g., "pt-br")
     * 2. Short language prefix (e.g., "pt")
     * 3. Default language defined in environment ("DEFAULT_LANGUAGE")
     *
     * @param string|null $lang Optional language code to force. If null, it will be auto-detected from the browser.
     * @return void
     */
    public static function load(?string $lang = null): void {
        // Use provided or detected language
        $lang = $lang ? strtolower($lang) : self::detect();

        // Try full code (e.g., pt-br.json)
        if (!empty($lang) && self::loadLangFiles($lang)) {
            self::$langCode = $lang;
            return;
        }

        // Try prefix fallback (e.g., pt.json)

        if (str_contains($lang, '-')) {
            $prefix = explode('-', $lang)[0];
            if (self::loadLangFiles($prefix)) {
                self::$langCode = $prefix;
                return;
            }
        }

        // Fallback to default language (from config)
        $fallback = self::defaultLang();
        if (!empty($fallback) && self::loadLangFiles($fallback)) {
            self::$langCode = $fallback;
            return;
        }

        // Nada encontrado
        self::$langCode = null;
        self::$translations = [];
    }

    /**
     * Get the default language code configured in the environment.
     *
     * @return string|null
     */
    public static function defaultLang(): ?string {
        $ret = Globals::env('DEFAULT_LANGUAGE');
        return empty($ret) ? null : strtolower(trim($ret));
    }

    /**
     * Detect the browser's preferred language via HTTP headers.
     *
     * Fallbacks to default language if none is detected.
     *
     * @return string|null
     */
    public static function detect(): ?string {
        $header = $_SERVER['HTTP_ACCEPT_LANGUAGE'] ?? '';
        return empty($header)
            ? self::defaultLang()
            : strtolower(trim(explode(',', $header)[0]));
    }
}
