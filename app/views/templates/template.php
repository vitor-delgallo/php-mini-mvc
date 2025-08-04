<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? lg("template.framework.name")) ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 2rem;
            background-color: #f9f9f9;
            color: #333;
        }

        header {
            border-bottom: 1px solid #ddd;
            margin-bottom: 2rem;
        }

        footer {
            border-top: 1px solid #ddd;
            margin-top: 2rem;
            padding-top: 1rem;
            font-size: 0.9rem;
            color: #777;
        }

        details {
            margin-bottom: 1.5rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            background-color: #f3f3f3; /* leve cinza de fundo para collapse */
            padding: 1rem;
        }

        summary {
            font-weight: bold;
            cursor: pointer;
            margin-bottom: .5rem;
        }

        article {
            background-color: #f6f6f6; /* fundo para separar cada artigo */
            padding: 1rem;
            border-radius: 4px;
            margin-top: 1rem;
        }

        code {
            display: block;
            background-color: #eaeaea; /* cinza levemente mais escuro */
            padding: 0.75rem;
            border-radius: 4px;
            margin: 0.5rem 0;
            font-family: Consolas, monospace;
            white-space: pre-wrap;
        }

        small {
            display: block;
            margin-top: 0.5rem;
            color: #555;
        }
    </style>

</head>
<body>
<header>
    <h1><?= htmlspecialchars($title ?? lg("template.framework.name")) ?></h1>
</header>

<main>
    <?php
        if (!empty($page)) {
            include \System\Core\Path::appViewsPages() . '/' . $page . '.php';
        } else if(!empty($html)) {
            echo $html;
        }
    ?>
</main>

<footer>
    &copy; <?= date('Y') . " - " . lg("template.framework.name") . " - " . lg("template.framework.simple.description") ?>
</footer>
</body>
</html>