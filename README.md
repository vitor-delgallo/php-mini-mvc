[📄 Leia-me em pt-br](README.pt-br.md)

# PHP Mini MVC Framework

A lightweight, modular PHP mini-framework built around the MVC architecture.
Designed for flexibility, readability, and rapid project setup — without heavy dependencies.

---

## 📑 Table of Contents

1. [Key Features](#️-key-features)
2. [Project Structure](#-project-structure)
3. [Getting Started](#-getting-started)
4. [Language Usage](#-language-usage)
5. [Routing Examples](#-routing-examples)
6. [Path Helper](#-path-helper)
7. [Database Usage](#-database-usage)
8. [Contributing](#-contributing)
9. [License](#-license)

---

## 🛠️ Key Features

* **Boot System** – Automatically loads any class inside `app/Bootable` on application start.
* **PSR-7 HTTP Handling** – Powered by [Laminas Diactoros](https://docs.laminas.dev/laminas-diactoros/).
* **Custom Session Handler** – Supports `files` or `database` drivers.
* **Advanced Language System**

    * Loads language files from `/languages`.
    * Adds nested key prefixes based on folder structure (e.g., `languages/users/admin/pt-br.json` → `users.admin.key`).
    * Supports key substitution: `lg("system.error.load", ["error" => $e->getMessage()])` replaces `{error}` in the string.
* **Automatic Helper Loading** – All PHP files inside `app/helpers` are autoloaded.
* **Middleware Support** – Easily register route-specific middlewares.
* **Form Validation** – Built-in validation rules and custom rule support.
* **Database Abstraction** – PDO wrapper with helper methods.
* **Clean Project Structure** – Autoloaded, organized, and environment-configurable.
* **View Rendering** – Shared variables, layouts, and templates.
* **Environment Variables** – `.env` configuration support.

---

## 📂 Project Structure

```
/ 
├── app/
│   ├── Controllers/
│   ├── Models/
│   ├── Middlewares/
│   ├── Bootable/
│   ├── helpers/
│   ├── views/
│   │   ├── pages/
│   │   └── templates/
│   └── routes/
├── languages/
├── public/
│   ├── assets/
│   │   ├── css/
│   │   ├── img/
│   │   ├── js/
│   │   └── libs/
│   └── index.php
├── storage/
│   ├── logs/
│   └── sessions/
├── system/
│   ├── Config/
│   ├── Core/
│   ├── Session/
│   ├── Interfaces/
│   ├── helpers/
│   └── includes/
├── vendor/
├── .env
├── .env.example
├── .gitignore
├── .htaccess
├── composer.json
├── composer.lock
└── LICENSE
└── README.md
└── README.pt-br.md
```

---

## 🚀 Getting Started

### 1. Installation

```bash
git clone https://github.com/vitor-delgallo/php-mini-mvc.git
cd php-mini-mvc
composer install
```

### 2. Environment Setup

Rename `.env.example` to `.env` and configure according to your needs.

---

## 🌍 Language Usage

**Example file:** `languages/system/en.json`

```json
{
  "error.load": "Error while loading: {error}"
}
```

**Usage with substitution:**

```php
lg("system.error.load", ["error" => $e->getMessage()]);
```

Folder prefixes are automatically applied based on structure.

---

## 🔎 Routing Examples

**Basic route:**

```php
$router->get('/', [HomeController::class, 'index']);
```

**With parameters:**

```php
$router->get('/user/{id}', [UserController::class, 'show']);
```

**With middleware:**

```php
$router->get('/dashboard', [DashboardController::class, 'index'])
       ->middleware([AuthMiddleware::class]);
```

**Middleware example:**

```php
namespace App\Middlewares;

use MiladRahimi\PhpRouter\Middleware;
use Psr\Http\Message\ServerRequestInterface;

class AuthMiddleware implements Middleware
{
    public function handle(ServerRequestInterface $request, callable $next)
    {
        if (!session_has('user_id')) {
            return response_redirect('/login');
        }
        return $next($request);
    }
}
```

---

## 📂 Path Helper

`path_base()` is recommended for generating local asset URLs:

```php
<script src="<?= path_base() ?>/assets/js/app.js"></script>
<link rel="stylesheet" href="<?= path_base() ?>/assets/css/style.css">
```

Ensures correct paths even when the app is hosted in a subdirectory.

---

## 📊 Database Usage

**Select all:**

```php
$users = Database::select("SELECT * FROM users");
```

**Select single row:**

```php
$user = Database::selectRow("SELECT * FROM users WHERE id = ?", [$id]);
```

**Insert:**

```php
Database::statement("INSERT INTO users (name, email) VALUES (?, ?)", [$name, $email]);
```

**With transactions:**

```php
Database::startTransaction();
try {
    Database::statement("UPDATE accounts SET balance = balance - 100 WHERE id = ?", [$from]);
    Database::statement("UPDATE accounts SET balance = balance + 100 WHERE id = ?", [$to]);
    Database::commitTransaction();
} catch (Exception $e) {
    Database::rollbackTransaction();
}
```

---

## 🤝 Contributing

We welcome contributions! You can:

* Suggest new features or improvements.
* Report bugs or unexpected behaviors.
* Share examples of how you’re using the framework.
* Submit pull requests for enhancements or fixes.

To contribute, open an issue or pull request on the [GitHub repository](https://github.com/vitor-delgallo/php-mini-mvc).

---

## 📜 License

This project is licensed under the [MIT License](LICENSE).
