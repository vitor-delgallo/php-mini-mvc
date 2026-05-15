<?php

namespace System\Core;

use System\Config\Globals;

/**
 * Language handler and translator for the application.
 *
 * Loads JSON-based translation files from the app and system language directories,
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
     * In-memory cache of discovered language files per language code.
     *
     * @var array<string, array<int, array{path: string, baseDir: string, sourcePrefix: string}>>
     */
    private static array $langFilesCache = [];

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
     * Retrieve all translations whose keys start with a normalized prefix.
     *
     * The returned array preserves full translation keys so PHP and JavaScript
     * consumers can use the same identifiers.
     *
     * @param string $prefix Translation key prefix, with or without trailing dot.
     * @param string|null $lang Optional language code to load before filtering.
     * @return array<string, string>
     */
    public static function getByPrefix(string $prefix, ?string $lang = null): array {
        $prefix = self::normalizePrefix($prefix);
        if ($prefix === '') {
            return [];
        }

        $translations = self::get(null, null, $lang);
        if (!is_array($translations)) {
            return [];
        }

        return array_filter(
            $translations,
            fn($value, $key) => str_starts_with((string) $key, $prefix),
            ARRAY_FILTER_USE_BOTH
        );
    }

    /**
     * Normalize a translation prefix to dot notation with one trailing dot.
     *
     * @param string $prefix
     * @return string
     */
    public static function normalizePrefix(string $prefix): string {
        $prefix = trim($prefix);
        $prefix = trim($prefix, '.');

        return $prefix === '' ? '' : $prefix . '.';
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
     * Get language source roots and the prefix applied to each source.
     *
     * @return array<int, array{path: string, sourcePrefix: string}>
     */
    private static function languageRoots(): array {
        return [
            ['path' => Path::appLanguages(), 'sourcePrefix' => 'app.'],
            ['path' => Path::systemLanguages(), 'sourcePrefix' => 'system.'],
        ];
    }

    /**
     * Recursively find all files with the exact language filename (e.g., pt-br.json).
     *
     * @param string $lang Language code
     * @return array<int, array{path: string, baseDir: string, sourcePrefix: string}>
     */
    private static function findLangFiles(string $lang): array {
        if (isset(self::$langFilesCache[$lang])) {
            return self::$langFilesCache[$lang];
        }

        $target = "{$lang}.json";
        $matches = [];

        foreach (self::languageRoots() as $root) {
            $directory = $root['path'];
            if (!is_dir($directory)) {
                continue;
            }

            $baseDir = realpath($directory);
            if ($baseDir === false) {
                continue;
            }

            $iterator = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($directory, \FilesystemIterator::SKIP_DOTS)
            );

            foreach ($iterator AS $file) {
                if (
                    $file->isFile() &&
                    $file->getExtension() === 'json' &&
                    $file->getFilename() === $target
                ) {
                    $matches[] = [
                        'path' => $file->getPathname(),
                        'baseDir' => $baseDir,
                        'sourcePrefix' => $root['sourcePrefix'],
                    ];
                }
            }
        }

        self::$langFilesCache[$lang] = $matches;
        return self::$langFilesCache[$lang];
    }

    /**
     * Adds source and subdirectory prefixes to all keys of a flat associative array.
     *
     * - If a key already starts with the source prefix, it will be left unchanged.
     * - If a key already starts with the subdirectory prefix, only the source prefix is added.
     * - Otherwise both source and subdirectory prefixes are added.
     *
     * Example:
     *   prefixKeys(["title" => "Dashboard"], "app.", "admin.") returns ["app.admin.title" => "Dashboard"]
     *
     * @param array $arr The original key-value array.
     * @param string $sourcePrefix The source prefix to prepend to each key.
     * @param string $relativePrefix The relative directory prefix to prepend after the source prefix.
     * @return array The new array with prefixed keys.
     */
    private static function prefixKeys(array $arr, string $sourcePrefix, string $relativePrefix = ''): array {
        $prefixed = [];
        foreach ($arr AS $key => $value) {
            if (str_starts_with($key, $sourcePrefix)) {
                $prefixed[$key] = $value;
                continue;
            }

            if (!empty($relativePrefix) && str_starts_with($key, $relativePrefix)) {
                $prefixed["{$sourcePrefix}{$key}"] = $value;
                continue;
            }

            $prefixed["{$sourcePrefix}{$relativePrefix}{$key}"] = $value;
        }
        return $prefixed;
    }

    /**
     * Loads and merges all translation files matching the given language code.
     *
     * - Recursively scans app and system language directories for files named exactly "<lang>.json".
     * - Keys are prefixed with the source and relative path (dot notation).
     *   Example: "app/languages/modules/admin/pt-br.json" will prefix keys with "app.modules.admin.".
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
        foreach ($files as $fileInfo) {
            $file = $fileInfo['path'];
            $json = json_decode(file_get_contents($file), true);
            if (!is_array($json)) {
                continue;
            }

            $fileDir = realpath(dirname($file));
            if ($fileDir === false) {
                continue;
            }

            $relativePath = trim(substr($fileDir, strlen($fileInfo['baseDir'])), DIRECTORY_SEPARATOR);
            $prefix = $relativePath ? str_replace(DIRECTORY_SEPARATOR, '.', $relativePath) . '.' : '';

            $json = self::prefixKeys($json, $fileInfo['sourcePrefix'], $prefix);

            self::$translations = array_merge(self::$translations, $json);
        }

        return true;
    }

    /**
     * Loads the appropriate translation files into memory.
     *
     * Enhanced behavior:
     * - Recursively searches app and system language roots for all JSON files with the exact name matching the language code (e.g., "pt-br.json").
     * - All matching files are loaded and merged into the translation array.
     * - Files are prefixed by source and relative folder path.
     *   Example: a file in "app/languages/admin/pt-br.json" will generate keys like "app.admin.title".
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
