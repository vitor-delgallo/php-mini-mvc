[📄 Leia-me em pt-br](README.pt-br.md)

# 🔩 PHP Mini MVC Framework

A lightweight and modular PHP mini-framework based on the MVC architecture.
Built for flexibility, readability, and quick project scaffolding without external dependencies.

---

## 📦 Features

* ⚙️ PSR-7 HTTP handling (via Laminas Diactoros)
* 🧠 Custom session handler (`files` or `database`)
* 🌐 Language detection and translation via JSON files
* 💃 Database abstraction (PDO with helper methods)
* 🧱 Route dispatcher (League\Route)
* 📂 Clean project structure with autoloading
* 🔐 Environment-based configuration
* 📑 View rendering with shared variables and layout support
* 📁 Simple `.env` config via `vlucas/phpdotenv`

---

## 🗂️ Project Structure

```
/
├── app/
│   ├── Controllers/
│   ├── Models/
│   ├── helpers/
│   ├── views/
│   │   ├── pages/
│   │   └── templates/
│   └── routes.php
├── languages/
├── public/
│   ├── assets/
│   │   ├── css/
│   │   ├── img/
│   │   ├── js/
│   │   └── libs/
├── storage/
│   ├── logs/
│   └── sessions/
├── system/
│   ├── Config/
│   ├── Core/
│   ├── Session/
│   ├── helpers/
│   └── includes/
├── vendor/
├── .env
├── .env.example
├── .gitignore
├── .htaccess
├── composer.json
├── composer.lock
├── index.php
└── README.md
└── README.pt-br.md
```

---

## 🚀 Getting Started

### 1. Clone and Install Dependencies

```bash
git clone https://github.com/your-username/php-mini-mvc.git
cd php-mini-mvc
composer install
```

### 2. Environment Configuration

Rename `.env.example` to `.env` and configure your environment!

---

## 🌍 Language Support

All translations are stored in `/languages` using JSON format.
The system auto-detects the browser language or uses the default language.

---

## 💡 Routing Example

```php
$router->map('GET', '/', [HomeController::class, 'index']);
$router->map('POST', '/login', [AuthController::class, 'login']);
```

---

## 📜 License

This project is open-source and available under the [MIT License](LICENSE).

---
