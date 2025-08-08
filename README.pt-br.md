[📄 Read me in English](README.md)

# 🔩 PHP Mini MVC Framework (Português)

Um mini-framework PHP leve e modular baseado na arquitetura MVC.
Projetado para flexibilidade, legibilidade e estruturação rápida de projetos, sem dependências externas complexas.

---

## 📦 Recursos

* ⚙️ Manipulação HTTP PSR-7 (via Laminas Diactoros)
* 🧠 Manipulador de sessão personalizado (`files` ou `database`)
* 🌐 Detecção de idioma e traduções via arquivos JSON
* 💃 Abstração de banco de dados com PDO (e métodos auxiliares)
* 🧱 Dispatcher de rotas (MiladRahimi\PhpRouter)
* 📂 Estrutura de projeto limpa com autoloading
* 🔐 Configuração baseada em ambiente
* 📑 Renderização de views com variáveis globais e suporte a layouts
* 📁 Configuração `.env` simples usando `vlucas/phpdotenv`

---

## 🗂️ Estrutura do Projeto

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

## 🚀 Primeiros Passos

### 1. Clone o Projeto e Instale as Dependências

```bash
git clone https://github.com/vitor-delgallo/php-mini-mvc.git
cd php-mini-mvc
composer install
```

### 2. Configuração do Ambiente

Renomeie `.env.example` para `.env` e configure conforme seu ambiente!

---

## 🌍 Suporte a Idiomas

As traduções ficam na pasta `/languages` em arquivos JSON.
O sistema detecta automaticamente o idioma do navegador ou usa o idioma padrão definido.

---

## 💡 Exemplo de Rota

```php
$router->map('GET', '/', [HomeController::class, 'index']);
$router->map('POST', '/login', [AuthController::class, 'login']);
```

---

## 📜 Licença

Este projeto é open-source e está sob a [Licença MIT](LICENSE).

---
