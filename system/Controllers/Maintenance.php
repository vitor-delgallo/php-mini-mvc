<?php

namespace System\Controllers;

use Psr\Http\Message\ResponseInterface;
use System\Core\Path;
use System\Core\Response;
use Throwable;

class Maintenance
{
    private const CLEANUP_NONCE_TTL = 1800;

    /**
     * Creates a short-lived nonce for the dangerous app cleanup form.
     */
    public static function createCleanupNonce(): string
    {
        $issuedAt = (string) time();
        $random = bin2hex(random_bytes(16));
        $payload = $issuedAt . ':' . $random;

        return base64_encode($payload . ':' . self::signCleanupPayload($payload));
    }

    public function cleanApp(): ResponseInterface
    {
        if (($_SERVER['REQUEST_METHOD'] ?? 'GET') !== 'POST') {
            return Response::json([
                'success' => false,
                'error' => 'method_not_allowed',
            ], 405);
        }

        $nonce = $_POST['nonce'] ?? '';

        if (!is_string($nonce) || !self::verifyCleanupNonce($nonce) || !self::isSameOriginRequest()) {
            return Response::json([
                'success' => false,
                'error' => 'invalid_nonce',
            ], 403);
        }

        try {
            $result = self::cleanupAppSkeleton();

            return Response::json([
                'success' => true,
                'cleaned' => $result['cleaned'],
                'skipped' => $result['skipped'],
                'rewritten' => $result['rewritten'],
            ]);
        } catch (Throwable $throwable) {
            return Response::json([
                'success' => false,
                'error' => 'cleanup_failed',
                'message' => $throwable->getMessage(),
            ], 500);
        }
    }

    /**
     * Removes app examples and user-facing app assets while keeping the MVC skeleton.
     *
     * @return array{cleaned: array<int, string>, skipped: array<int, string>, rewritten: array<int, string>}
     */
    private static function cleanupAppSkeleton(): array
    {
        $result = [
            'cleaned' => [],
            'skipped' => [],
            'rewritten' => [],
        ];

        $requiredGitkeepDirs = [
            ['path' => Path::appBootable(), 'base' => Path::app(), 'label' => 'app/Bootable'],
            ['path' => Path::appControllers(), 'base' => Path::app(), 'label' => 'app/Controllers'],
            ['path' => Path::appMiddlewares(), 'base' => Path::app(), 'label' => 'app/Middlewares'],
            ['path' => Path::appModels(), 'base' => Path::app(), 'label' => 'app/Models'],
            ['path' => Path::appHelpers(), 'base' => Path::app(), 'label' => 'app/helpers'],
            ['path' => Path::appLanguages(), 'base' => Path::app(), 'label' => 'app/languages'],
            ['path' => Path::appViewsPages(), 'base' => Path::appViews(), 'label' => 'app/views/pages'],
            ['path' => Path::appViewsTemplates(), 'base' => Path::appViews(), 'label' => 'app/views/templates'],
        ];

        foreach ($requiredGitkeepDirs as $directory) {
            self::cleanDirectory($directory['path'], $directory['base'], $directory['label'], true, true, $result);
        }

        $emptyDirs = [
            ['path' => Path::storageLogs(), 'base' => Path::storage(), 'label' => 'storage/logs', 'create' => true],
            ['path' => Path::storageSessions(), 'base' => Path::storage(), 'label' => 'storage/sessions', 'create' => true],
            ['path' => Path::public() . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'css' . DIRECTORY_SEPARATOR, 'base' => Path::public() . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR, 'label' => 'public/assets/css', 'create' => true],
            ['path' => Path::public() . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'js' . DIRECTORY_SEPARATOR, 'base' => Path::public() . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR, 'label' => 'public/assets/js', 'create' => true],
            ['path' => Path::public() . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'libs' . DIRECTORY_SEPARATOR, 'base' => Path::public() . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR, 'label' => 'public/assets/libs', 'create' => true],
            ['path' => Path::public() . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR, 'base' => Path::public() . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR, 'label' => 'public/assets/img', 'create' => true],
        ];

        foreach ($emptyDirs as $directory) {
            self::cleanDirectory($directory['path'], $directory['base'], $directory['label'], $directory['create'], false, $result);
        }

        $resourceVue = Path::root() . DIRECTORY_SEPARATOR . 'resources' . DIRECTORY_SEPARATOR . 'vue' . DIRECTORY_SEPARATOR;
        self::cleanDirectory(
            $resourceVue . 'pages' . DIRECTORY_SEPARATOR,
            $resourceVue,
            'resources/vue/pages',
            is_dir($resourceVue),
            true,
            $result
        );

        self::cleanDirectory(
            Path::languages() . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR,
            Path::languages(),
            'languages/app',
            false,
            true,
            $result
        );

        self::rewriteAppRoutes($result);
        self::copySystemTemplateToApp($result);

        return $result;
    }

    /**
     * @param array{cleaned: array<int, string>, skipped: array<int, string>, rewritten: array<int, string>} $result
     */
    private static function cleanDirectory(
        string $directory,
        string $baseDirectory,
        string $label,
        bool $createIfMissing,
        bool $writeGitkeep,
        array &$result
    ): void {
        if (!is_dir($directory)) {
            if (!$createIfMissing) {
                $result['skipped'][] = $label;
                return;
            }

            if (!is_dir($baseDirectory)) {
                mkdir($baseDirectory, 0755, true);
            }

            mkdir($directory, 0755, true);
        }

        $safeDirectory = self::assertDirectoryInside($directory, $baseDirectory, $label);

        foreach (scandir($safeDirectory) ?: [] as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }

            self::deletePath($safeDirectory . DIRECTORY_SEPARATOR . $entry, $safeDirectory);
        }

        if ($writeGitkeep) {
            file_put_contents($safeDirectory . DIRECTORY_SEPARATOR . '.gitkeep', '');
        }

        $result['cleaned'][] = $label;
    }

    private static function rewriteAppRoutes(array &$result): void
    {
        self::writeFileInside(
            Path::appRoutes() . DIRECTORY_SEPARATOR . 'web.php',
            Path::appRoutes(),
            "<?php\n\n/**\n * @var MiladRahimi\\PhpRouter\\Router \$router\n * The application's main router instance.\n */\n\$router->get('/', function () {\n    return \\System\\Core\\Response::redirect('/web-system');\n});\n",
            'app/routes/web.php'
        );

        self::writeFileInside(
            Path::appRoutes() . DIRECTORY_SEPARATOR . 'api.php',
            Path::appRoutes(),
            "<?php\n\n/**\n * Application API routes were cleared by the system cleanup tool.\n * Add API routes here when needed.\n */\n",
            'app/routes/api.php'
        );

        $result['rewritten'][] = 'app/routes/web.php';
        $result['rewritten'][] = 'app/routes/api.php';
    }

    private static function copySystemTemplateToApp(array &$result): void
    {
        $source = Path::systemViewsTemplates() . DIRECTORY_SEPARATOR . 'template.php';
        $target = Path::appViewsTemplates() . DIRECTORY_SEPARATOR . 'template.php';

        if (!is_file($source)) {
            throw new \RuntimeException('System template was not found.');
        }

        self::assertDirectoryInside(dirname($target), Path::appViews(), 'app/views/templates');
        copy($source, $target);

        $result['rewritten'][] = 'app/views/templates/template.php';
    }

    private static function writeFileInside(string $path, string $baseDirectory, string $content, string $label): void
    {
        if (!is_dir($baseDirectory)) {
            mkdir($baseDirectory, 0755, true);
        }

        $directory = dirname($path);

        if (!is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        self::assertDirectoryInside($directory, $baseDirectory, $label);
        file_put_contents($path, $content);
    }

    private static function deletePath(string $path, string $baseDirectory): void
    {
        if (is_link($path) || is_file($path)) {
            unlink($path);
            return;
        }

        if (!is_dir($path)) {
            return;
        }

        $directory = self::assertDirectoryInside($path, $baseDirectory, $path);

        foreach (scandir($directory) ?: [] as $entry) {
            if ($entry === '.' || $entry === '..') {
                continue;
            }

            self::deletePath($directory . DIRECTORY_SEPARATOR . $entry, $baseDirectory);
        }

        rmdir($directory);
    }

    private static function assertDirectoryInside(string $directory, string $baseDirectory, string $label): string
    {
        $realDirectory = realpath($directory);
        $realBase = realpath($baseDirectory);

        if ($realDirectory === false || $realBase === false) {
            throw new \RuntimeException('Unable to resolve cleanup path: ' . $label);
        }

        $normalizedDirectory = self::normalizePath($realDirectory);
        $normalizedBase = rtrim(self::normalizePath($realBase), '/') . '/';

        if ($normalizedDirectory !== rtrim($normalizedBase, '/') && !str_starts_with($normalizedDirectory . '/', $normalizedBase)) {
            throw new \RuntimeException('Unsafe cleanup path blocked: ' . $label);
        }

        return $realDirectory;
    }

    private static function verifyCleanupNonce(string $nonce): bool
    {
        $decoded = base64_decode($nonce, true);

        if (!is_string($decoded)) {
            return false;
        }

        $parts = explode(':', $decoded);

        if (count($parts) !== 3) {
            return false;
        }

        [$issuedAt, $random, $signature] = $parts;

        if (!ctype_digit($issuedAt) || $random === '') {
            return false;
        }

        if ((time() - (int) $issuedAt) > self::CLEANUP_NONCE_TTL) {
            return false;
        }

        return hash_equals(self::signCleanupPayload($issuedAt . ':' . $random), $signature);
    }

    private static function signCleanupPayload(string $payload): string
    {
        $secret = getenv('SYSTEM_TOKEN') ?: getenv('APP_KEY') ?: (Path::root() . '|' . __FILE__);

        return hash_hmac('sha256', $payload, $secret);
    }

    private static function isSameOriginRequest(): bool
    {
        $origin = $_SERVER['HTTP_ORIGIN'] ?? '';
        $referer = $_SERVER['HTTP_REFERER'] ?? '';
        $candidate = is_string($origin) && $origin !== '' ? $origin : $referer;

        if (!is_string($candidate) || $candidate === '') {
            return true;
        }

        $candidateParts = parse_url($candidate);
        $host = $_SERVER['HTTP_HOST'] ?? '';

        if (!is_array($candidateParts) || !is_string($host) || $host === '') {
            return false;
        }

        $requestScheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $candidateScheme = $candidateParts['scheme'] ?? $requestScheme;
        $candidateHost = $candidateParts['host'] ?? '';
        $candidatePort = $candidateParts['port'] ?? self::defaultPort($candidateScheme);
        $requestParts = parse_url($requestScheme . '://' . $host);
        $requestHost = $requestParts['host'] ?? '';
        $requestPort = $requestParts['port'] ?? self::defaultPort($requestScheme);

        return $candidateScheme === $requestScheme
            && strtolower((string) $candidateHost) === strtolower((string) $requestHost)
            && (int) $candidatePort === (int) $requestPort;
    }

    private static function defaultPort(string $scheme): int
    {
        return $scheme === 'https' ? 443 : 80;
    }

    private static function normalizePath(string $path): string
    {
        return str_replace('\\', '/', $path);
    }
}
