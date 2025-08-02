<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Mini-MVC') ?></title>
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
    </style>
</head>
<body>
<header>
    <h1><?= htmlspecialchars($title ?? 'Mini-MVC') ?></h1>
</header>

<main>
    <?php
        if (!empty($page)) {
            include \System\Core\Path::viewsPages() . '/' . $page . '.php';
        }
    ?>
</main>

<footer>
    &copy; <?= date('Y') ?> - Mini-MVC - Um micro framework PHP simples
</footer>
</body>
</html>