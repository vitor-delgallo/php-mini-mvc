<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? lg("template.framework.name")) ?></title>
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
            <h1><?= htmlspecialchars($title ?? lg("template.framework.name")) ?></h1>
        </div>
    </header>
    <main>
        <div class="container">
            <?php
            if (!empty($page)) {
                include path_app_views_pages() . '/' . $page . '.php';
            } elseif (!empty($html)) {
                echo $html;
            }
            ?>
        </div>
    </main>
    <footer>
        <div class="container">
            &copy; <?= date('Y') . " - " . lg("template.framework.name") . " - " . lg("template.framework.simple.description") ?>
        </div>
    </footer>
</body>
</html>