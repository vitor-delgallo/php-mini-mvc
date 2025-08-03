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
     * Retrieve a translated string by key, or return all translations.
     *
     * If no language is loaded yet, or a different one is requested,
     * the appropriate language will be (re)loaded.
     *
     * @param string|null $key  Translation key to retrieve (optional).
     * @param string|null $lang Language code to force reload (optional).
     * @return string|array|null
     */
    public static function get(?string $key = null, ?string $lang = null): string|array|null {
        if (empty(self::$translations) || (!empty($lang) && self::currentLang() !== $lang)) {
            self::load();
        }

        return $key ? (self::$translations[$key] ?? null) : self::$translations;
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
     * Load the appropriate translation file into memory.
     *
     * Load priority:
     * 1. Exact match (e.g., pt-br.json)
     * 2. Short code fallback (e.g., pt.json)
     * 3. Configured default language
     *
     * @param string|null $lang Language code to load (optional).
     * @return void
     */
    public static function load(?string $lang = null): void {
        $available = glob(Path::languages() . '/*.json');
        if (!$available) {
            return;
        }

        // If there's only one language file, use it
        if (count($available) === 1) {
            self::$langCode = pathinfo($available[0], PATHINFO_FILENAME);
            self::$translations = json_decode(file_get_contents($available[0]), true);
            return;
        }

        // Use provided or detected language
        $lang = $lang ? strtolower($lang) : self::detect();

        if (!empty($lang)) {
            // Try full code (e.g., pt-br.json)
            $fullPath = Path::languages() . "/{$lang}.json";
            if (file_exists($fullPath)) {
                self::$langCode = $lang;
                self::$translations = json_decode(file_get_contents($fullPath), true);
                return;
            }

            // Try prefix fallback (e.g., pt.json)
            if (str_contains($lang, '-')) {
                $prefix = explode('-', $lang)[0];
                $shortPath = Path::languages() . "/{$prefix}.json";
                if (file_exists($shortPath)) {
                    self::$langCode = $prefix;
                    self::$translations = json_decode(file_get_contents($shortPath), true);
                    return;
                }
            }
        }

        // Fallback to default language (from config)
        $fallback = self::defaultLang();
        if (!empty($fallback)) {
            $fallbackPath = Path::languages() . "/{$fallback}.json";
            if (file_exists($fallbackPath)) {
                self::$langCode = $fallback;
                self::$translations = json_decode(file_get_contents($fallbackPath), true);
                return;
            }
        }

        // No translation found
        self::$langCode = null;
        return;
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
