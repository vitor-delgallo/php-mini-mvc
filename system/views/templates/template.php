<?php
$vueRender = (!empty($vue) && is_array($vue)) ? $vue : null;
$vueAssets = null;

if ($vueRender !== null) {
    $vueEntrypoint = trim((string) ($vueRender['entrypoint'] ?? 'main.js'), '/');
    $viteDevServer = function_exists('globals_env') ? trim((string) (globals_env('VITE_DEV_SERVER') ?? '')) : '';

    if ($viteDevServer !== '') {
        $viteDevServer = rtrim($viteDevServer, '/');
        $vueAssets = [
            'dev' => true,
            'client' => $viteDevServer . '/@vite/client',
            'entrypoint' => $viteDevServer . '/resources/vue/' . $vueEntrypoint,
            'css' => [],
        ];
    } else {
        $manifestPath = path_public() . '/build/.vite/manifest.json';

        if (!is_file($manifestPath)) {
            throw new \RuntimeException('Vite manifest not found for Vue rendering. Run `npm run build` or configure VITE_DEV_SERVER.');
        }

        $manifest = json_decode((string) file_get_contents($manifestPath), true);
        if (!is_array($manifest)) {
            throw new \RuntimeException('Vite manifest is invalid for Vue rendering.');
        }

        $manifestKey = 'resources/vue/' . $vueEntrypoint;
        if (empty($manifest[$manifestKey]) || empty($manifest[$manifestKey]['file'])) {
            throw new \RuntimeException("Vite entrypoint `{$manifestKey}` was not found in the manifest.");
        }

        $assetBase = rtrim(path_base_public(), '/') . '/build/';
        $vueAssets = [
            'dev' => false,
            'entrypoint' => $assetBase . ltrim($manifest[$manifestKey]['file'], '/'),
            'css' => array_map(
                fn(string $file): string => $assetBase . ltrim($file, '/'),
                $manifest[$manifestKey]['css'] ?? []
            ),
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="robots" content="noindex, nofollow, noarchive, nosnippet, noimageindex">
    <meta name="googlebot" content="noindex, nofollow, noarchive, nosnippet, noimageindex">
    <meta name="bingbot" content="noindex, nofollow, noarchive, nosnippet, noimageindex">
    <title><?= htmlspecialchars($title ?? lg("system.template.framework.name")) ?></title>
    <?php if (!empty($vueAssets['css'])): ?>
        <?php foreach ($vueAssets['css'] as $cssFile): ?>
            <link rel="stylesheet" href="<?= htmlspecialchars($cssFile) ?>">
        <?php endforeach; ?>
    <?php endif; ?>
    <style>
        :root {
            --bg-color: #f9f9f9;
            --primary-color: #333;
            --secondary-color: #666;
            --accent-color: #007bff;
            --border-radius: 5px;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background-color: var(--bg-color);
            color: var(--primary-color);
            line-height: 1.6;
        }

        header {
            background: #fff;
            border-bottom: 1px solid #eee;
            padding: 1rem 0;
            margin-bottom: 1rem;
        }

        header h1 {
            margin: 0;
            font-size: 2rem;
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 0 1rem;
        }

        main {
            padding: 1rem 0;
        }

        footer {
            background: #fff;
            border-top: 1px solid #eee;
            padding: 1rem 0;
            margin-top: 2rem;
            font-size: 0.9rem;
            color: var(--secondary-color);
        }

        details {
            border: 1px solid #ddd;
            border-radius: var(--border-radius);
            padding: 1rem;
            margin-bottom: 1rem;
            background: #f5f5f5;
        }

        summary {
            cursor: pointer;
            font-weight: bold;
            position: relative;
            padding-right: 1rem;
        }

        /* Remove default triangle marker */
        summary::marker {
            display: none;
        }

        /* Custom arrow indicator */
        summary::after {
            content: '\25BC';
            position: absolute;
            right: 0;
            transform: rotate(0deg);
            transition: transform 0.2s ease;
        }

        /* Rotate arrow when open */
        details[open] summary::after {
            transform: rotate(180deg);
        }

        /* Nested details spacing */
        details details {
            margin-top: 1rem;
        }

        code {
            display: block;
            background: #eaeaea;
            padding: 0.75rem;
            border-radius: var(--border-radius);
            overflow-x: auto;
            white-space: pre-wrap;
            margin: 0.5rem 0;
            font-family: Consolas, monospace;
            font-size: 0.9rem;
        }

        small {
            display: block;
            color: var(--secondary-color);
            margin-top: 0.5rem;
        }

        @media (max-width: 600px) {
            header h1 {
                font-size: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1><?= htmlspecialchars($title ?? lg("system.template.framework.name")) ?></h1>
        </div>
    </header>
    <main>
        <div class="container">
            <?php
            if (!empty($page)) {
                include $__viewPagesPath . '/' . $page . '.php';
            } elseif (!empty($html)) {
                echo $html;
            } elseif ($vueRender !== null) {
                $vueBootPayload = [
                    'page' => $vueRender['page'] ?? '',
                    'props' => $vueRender['props'] ?? [],
                    'meta' => $vueRender['meta'] ?? [],
                ];
                $vueBootJson = json_encode(
                    $vueBootPayload,
                    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_HEX_QUOT
                );
                ?>
                <div id="php-mini-mvc-vue"></div>
                <script type="application/json" id="php-mini-mvc-vue-data"><?= $vueBootJson ?></script>
                <?php if (!empty($vueAssets['dev'])): ?>
                    <script type="module" src="<?= htmlspecialchars($vueAssets['client']) ?>"></script>
                <?php endif; ?>
                <script type="module" src="<?= htmlspecialchars($vueAssets['entrypoint']) ?>"></script>
                <?php
            }
            ?>
        </div>
    </main>
    <footer>
        <div class="container">
            &copy; <?= date('Y') . " - " . lg("system.template.framework.name") . " - " . lg("system.template.framework.simple.description") ?>
        </div>
    </footer>
</body>
</html>
