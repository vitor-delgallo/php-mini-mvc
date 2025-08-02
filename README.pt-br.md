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
* 🧱 Dispatcher de rotas (League\Route)
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
│   ├── helpers/
│   └── views/
│       ├── pages/
│       └── templates/
│   └── routes.php
├── langs/
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
```

---

## 🚀 Primeiros Passos

### 1. Clone o Projeto e Instale as Dependências

```bash
git clone https://github.com/seu-usuario/php-mini-mvc.git
cd php-mini-mvc
composer install
```

### 2. Configuração do Ambiente

Renomeie `.env.example` para `.env` e configure conforme seu ambiente!

---

## 🌍 Suporte a Idiomas

As traduções ficam na pasta `/langs` em arquivos JSON.
O sistema detecta automaticamente o idioma do navegador ou usa o idioma padrão definido.

---

## 💡 Exemplo de Rota

```php
$router->map('GET', '/', [HomeController::class, 'index']);
$router->map('POST', '/login', [AuthController::class, 'login']);
```

---

## 🔧 Utilitários Disponíveis

* `Path::viewsTemplates()` – retorna o caminho absoluto para templates
* `Globals::get('CHAVE')` – acessa variáveis de ambiente/config
* `Session::set('chave', 'valor')` – armazena dados em sessão
* `Language::get('bem_vindo')` – recupera termos traduzidos

---

## 📜 Licença

Este projeto é open-source e está sob a [Licença MIT](LICENSE).

---
