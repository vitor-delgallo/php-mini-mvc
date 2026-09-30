<?php
namespace System\Core;

use InvalidArgumentException;
use RuntimeException;
use System\Config\Globals;

/**
 * View rendering engine for the application.
 *
 * This class provides a simple and flexible way to render HTML views with optional layout templates.
 * It supports sharing global variables across all views and rendering individual pages with local data.
 *
 * Core features:
 * - Define a layout template (with support for subdirectories)
 * - Render pages with shared and scoped data
 * - Manage global variables used in views
 *
 * Template files are expected to live under `views/templates/`.
 * Page views are expected to live under `views/pages/`.
 */
class View {
    /**
     * Global variables shared across all views.
     *
     * These variables will be extracted into scope when rendering templates.
     */
    private static array $globals = [];

    /**
     * Relative path to the main layout/template file.
     */
    private static ?string $template = null;

    /**
     * Share a global variable with all views.
     *
     * @param string $key   The variable name.
     * @param mixed  $value The value to be shared.
     */
    public static function share(string $key, mixed $value): void {
        self::$globals[$key] = $value;
    }

    /**
     * Share multiple global variables at once.
     *
     * @param array $items Associative array of key => value pairs.
     */
    public static function shareMany(array $items): void {
        foreach ($items as $key => $value) {
            self::share($key, $value);
        }
    }

    /**
     * Remove a single shared global variable.
     *
     * @param string $key The variable name to remove.
     */
    public static function forget(string $key): void {
        unset(self::$globals[$key]);
    }

    /**
     * Remove multiple shared global variables at once.
     *
     * @param array $keys List of variable names to remove.
     */
    public static function forgetMany(array $keys): void {
        foreach ($keys as $key) {
            self::forget($key);
        }
    }

    /**
     * Clear all shared global variables.
     */
    public static function clear(): void {
        self::$globals = [];
    }

    /**
     * Set the path to the main layout/template file used for rendering views.
     *
     * @param string|null $relativePath Path relative to the templates directory.
     */
    public static function setTemplate(?string $relativePath = null): void {
        $template = self::normalizePhpViewPath($relativePath, 'template', true);
        self::$template = "/" . $template . ".php";
    }

    /**
     * Get the current template path, initializing it with the default if necessary.
     *
     * @return string The relative path to the template file.
     */
    public static function getTemplate(): string {
        if(empty(self::$template)) {
            self::setTemplate();
        }

        return self::$template;
    }

    /**
     * Render a view file or html within the main layout template.
     *
     * Variables passed through `$data` and shared globals will be extracted.
     *
     * @param string|null $page The relative view path (without extension).
     * @param string|null $html HTML content to be included
     * @param array  $data The data to be extracted into the view.
     * @return string Rendered HTML content.
     */
    private static function render(
        ?string $page = null,
        ?string $html = null,
        array $data = [],
        ?string $pagesPath = null,
        ?string $templatesPath = null
    ): string {
        // Resolve the internal paths first, so shared or page data can never redirect an include
        $__viewPagesPath = $pagesPath ?? Path::appViewsPages();
        $__viewTemplatesPath = $templatesPath ?? Path::appViewsTemplates();
        $__viewPage = $page !== null ? self::normalizePhpViewPath($page, 'page') : null;
        $__viewPageFile = $__viewPage !== null
            ? self::resolvePhpViewFile($__viewPagesPath, $__viewPage, 'page')
            : null;
        $__viewHtml = $html;
        $__viewTemplate = self::normalizePhpViewPath(ltrim(self::getTemplate(), '/'), 'template', true);
        $__viewTemplatePath = self::resolvePhpViewFile($__viewTemplatesPath, $__viewTemplate, 'template');

        // Shared globals and local data become variables (e.g. $data['user'] → $user). Names already
        // in use here are skipped, so the arguments and the $__view* variables above keep their values.
        extract(array_merge(self::getGlobals(), $data), EXTR_SKIP);

        // Keep legacy template variables available.
        $page = $__viewPage;
        $html = $__viewHtml;

        // Capture the template output; on failure, drop the partial output before rethrowing
        ob_start();
        try {
            include $__viewTemplatePath;
        } catch (\Throwable $exception) {
            ob_end_clean();
            throw $exception;
        }

        return ob_get_clean();
    }

    /**
     * Normalize a PHP page/template path and reject traversal or absolute paths.
     */
    private static function normalizePhpViewPath(?string $path, string $label, bool $allowDefault = false): string {
        if ($path === null || trim($path) === '') {
            if ($allowDefault) {
                return 'template';
            }

            throw new InvalidArgumentException("Invalid {$label} path.");
        }

        if (str_contains($path, "\0")) {
            throw new InvalidArgumentException("Invalid {$label} path.");
        }

        $normalized = str_replace("\\", "/", trim($path));

        if (
            $normalized === '' ||
            str_starts_with($normalized, '/') ||
            str_starts_with($normalized, '//') ||
            preg_match('#^[A-Za-z]:/#', $normalized)
        ) {
            throw new InvalidArgumentException("Invalid {$label} path.");
        }

        $normalized = trim($normalized, '/');
        $normalized = preg_replace('#/+#', '/', $normalized) ?? '';

        $extension = pathinfo($normalized, PATHINFO_EXTENSION);
        if ($extension !== '') {
            if (strtolower($extension) !== 'php') {
                throw new InvalidArgumentException("Invalid {$label} path extension.");
            }

            $normalized = substr($normalized, 0, -1 * (strlen($extension) + 1));
        }

        $segments = explode('/', $normalized);
        foreach ($segments as $segment) {
            if (
                $segment === '' ||
                $segment === '.' ||
                $segment === '..' ||
                !preg_match('/^[A-Za-z0-9_-]+$/', $segment)
            ) {
                throw new InvalidArgumentException("Invalid {$label} path.");
            }
        }

        return implode('/', $segments);
    }

    /**
     * Resolve a normalized PHP view path and ensure it remains inside the base directory.
     */
    private static function resolvePhpViewFile(string $basePath, string $relativePath, string $label): string {
        $base = realpath($basePath);

        if ($base === false || !is_dir($base)) {
            throw new RuntimeException("Invalid {$label} base path.");
        }

        $candidate = $base . DIRECTORY_SEPARATOR . str_replace('/', DIRECTORY_SEPARATOR, $relativePath) . '.php';
        $resolved = realpath($candidate);

        if ($resolved === false || !is_file($resolved) || !self::isPathInside($resolved, $base)) {
            throw new RuntimeException("Invalid {$label} file.");
        }

        return $resolved;
    }

    private static function isPathInside(string $path, string $basePath): bool {
        $normalizedPath = str_replace("\\", "/", $path);
        $normalizedBase = rtrim(str_replace("\\", "/", $basePath), '/') . '/';

        return str_starts_with($normalizedPath, $normalizedBase);
    }

    /**
     * Normalize Vue resource paths and reject traversal attempts.
     *
     * @param string $path Path relative to the expected Vue resource directory.
     * @param string $label Human-readable path label for error messages.
     * @return string
     */
    private static function normalizeVuePath(string $path, string $label): string {
        $normalized = str_replace("\\", "/", $path);
        $normalized = trim($normalized, "/ \t\n\r\0\x0B");
        $normalized = preg_replace('#/+#', '/', $normalized) ?? "";

        if (
            empty($normalized) ||
            str_contains($normalized, "\0") ||
            preg_match('#(^|/)\.\.?(/|$)#', $normalized)
        ) {
            throw new InvalidArgumentException("Invalid Vue {$label} path.");
        }

        return $normalized;
    }

    /**
     * Normalize a Vue page path relative to resources/vue/pages.
     *
     * @param string $page Path with or without .vue extension.
     * @return string
     */
    private static function normalizeVuePage(string $page): string {
        $normalized = self::normalizeVuePath($page, 'page');

        if (str_ends_with(strtolower($normalized), '.vue')) {
            $normalized = substr($normalized, 0, -4);
        }

        return self::normalizeVuePath($normalized, 'page');
    }

    /**
     * Normalize a Vue entrypoint path relative to resources/vue.
     *
     * @param string|null $entrypoint Entrypoint path, defaulting to main.js.
     * @return string
     */
    private static function normalizeVueEntrypoint(?string $entrypoint = null): string {
        return self::normalizeVuePath($entrypoint ?: 'main.js', 'entrypoint');
    }

    /**
     * Normalize i18n prefixes requested by a Vue page.
     *
     * @param array|string|null $prefixes One prefix or a list of prefixes.
     * @return array<int, string>
     */
    private static function normalizeVueI18nPrefixes(array|string|null $prefixes): array {
        if ($prefixes === null) {
            return [];
        }

        if (is_string($prefixes)) {
            $prefixes = [$prefixes];
        }

        $normalized = [];
        foreach ($prefixes as $prefix) {
            if (!is_string($prefix)) {
                continue;
            }

            $prefix = rtrim(Language::normalizePrefix($prefix), '.');
            if ($prefix !== '') {
                $normalized[$prefix] = $prefix;
            }
        }

        return array_values($normalized);
    }

    /**
     * Build the optional Vue i18n boot payload.
     *
     * @param array|string|null $prefixes Translation prefixes requested by the page.
     * @param string|null $lang Optional language override.
     * @return array<string, mixed>
     */
    private static function vueI18nPayload(array|string|null $prefixes, ?string $lang = null): array {
        $prefixes = self::normalizeVueI18nPrefixes($prefixes);
        if (empty($prefixes)) {
            return [
                'enabled' => false,
                'prefixes' => [],
            ];
        }

        $token = Globals::env('SYSTEM_TOKEN');
        $token = is_string($token) ? trim($token) : '';

        if ($token === '') {
            return [
                'enabled' => false,
                'prefixes' => $prefixes,
            ];
        }

        return [
            'enabled' => true,
            'endpoint' => Path::basePath() . Globals::getSystemApiPrefix() . '/i18n',
            'prefixes' => $prefixes,
            'lang' => $lang ?: Language::currentLang() ?: Language::defaultLang(),
            'token' => $token,
        ];
    }

    /**
     * Render a view file within the main layout template.
     *
     * Variables passed through `$data` and shared globals will be extracted.
     *
     * @param string $page The relative view path (without extension).
     * @param array  $data The data to be extracted into the view.
     * @return string Rendered HTML content.
     */
    public static function render_page(string $page, array $data = []): string {
        return self::render($page, null, $data);
    }

    /**
     * Render a system view file within the system layout template.
     *
     * Variables passed through `$data` and shared globals will be extracted.
     *
     * @param string $page The relative system view path (without extension).
     * @param array  $data The data to be extracted into the view.
     * @return string Rendered HTML content.
     */
    public static function render_system_page(string $page, array $data = []): string {
        return self::render($page, null, $data, Path::systemViewsPages(), Path::systemViewsTemplates());
    }

    /**
     * Render a html within the main layout template.
     *
     * Variables passed through `$data` and shared globals will be extracted.
     *
     * @param string $html HTML content to be included
     * @param array  $data The data to be extracted into the view.
     * @return string Rendered HTML content.
     */
    public static function render_html(string $html, array $data = []): string {
        return self::render(null, $html, $data);
    }

    /**
     * Render a Vue page within the main layout template.
     *
     * @param string $page Path relative to resources/vue/pages, with or without .vue.
     * @param array $data Props passed to the Vue page component.
     * @param string|null $entrypoint Vite entrypoint relative to resources/vue.
     * @param array|string|null $i18nPrefixes Translation prefixes requested by the Vue page.
     * @param string|null $lang Optional language code for i18n fetches.
     * @return string Rendered HTML content.
     */
    public static function render_vue(
        string $page,
        array $data = [],
        ?string $entrypoint = null,
        array|string|null $i18nPrefixes = null,
        ?string $lang = null
    ): string {
        $normalizedPage = self::normalizeVuePage($page);
        $normalizedEntrypoint = self::normalizeVueEntrypoint($entrypoint);

        return self::render(null, null, [
            'vue' => [
                'page' => $normalizedPage,
                'props' => $data,
                'entrypoint' => $normalizedEntrypoint,
                'i18n' => self::vueI18nPayload($i18nPrefixes, $lang),
                'meta' => [
                    'basePath' => Path::basePath(),
                    'basePublicPath' => Path::basePathPublic(),
                    'entrypoint' => $normalizedEntrypoint,
                ],
            ],
        ]);
    }

    /**
     * Retrieve all current shared global variables.
     *
     * @return array Associative array of globals.
     */
    public static function getGlobals(): array {
        return self::$globals;
    }
}
