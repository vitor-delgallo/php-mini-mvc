[📄 Read me in English](README.md)

# PHP Mini MVC Framework

Um mini-framework PHP modular e leve, baseado na arquitetura MVC.
Projetado para flexibilidade, legibilidade e configuração rápida de projetos — sem dependências pesadas.

---

## 📑 Índice

1. [Recursos Principais](#️-recursos-principais)
2. [Estrutura do Projeto](#-estrutura-do-projeto)
3. [Primeiros Passos](#-primeiros-passos)
4. [Uso de Idiomas](#-uso-de-idiomas)
5. [Exemplos de Rotas](#-exemplos-de-rotas)
6. [Helper de Caminho](#-helper-de-caminho)
7. [Uso do Banco de Dados](#-uso-do-banco-de-dados)
8. [Contribuindo](#-contribuindo)
9. [Licença](#-licença)

---

## 🛠️ Recursos Principais

* **Sistema de Boot** – Carrega automaticamente qualquer classe dentro de `app/Bootable` na inicialização da aplicação.
* **Manipulação HTTP PSR-7** – Alimentado por [Laminas Diactoros](https://docs.laminas.dev/laminas-diactoros/).
* **Manipulador de Sessão Personalizado** – Suporte aos drivers `files` ou `database`.
* **Sistema de Idiomas Avançado**

    * Carrega arquivos de idioma da pasta `/languages`.
    * Adiciona prefixos de chave aninhados com base na estrutura de pastas (ex.: `languages/users/admin/pt-br.json` → `users.admin.key`).
    * Suporte a substituição de chaves: `lg("system.error.load", ["error" => $e->getMessage()])` substitui `{error}` na string.
* **Carregamento Automático de Helpers** – Todos os arquivos PHP em `app/helpers` são carregados automaticamente.
* **Suporte a Middlewares** – Registre facilmente middlewares específicos por rota.
* **Validação de Formulários** – Regras de validação integradas e suporte a regras personalizadas.
* **Abstração de Banco de Dados** – Wrapper PDO com métodos auxiliares.
* **Estrutura Limpa de Projeto** – Autocarregada, organizada e configurável por ambiente.
* **Renderização de Views** – Variáveis globais, layouts e templates.
* **Variáveis de Ambiente** – Suporte a configuração via `.env`.

---

## 📂 Estrutura do Projeto

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

### 1. Instalação

```bash
git clone https://github.com/vitor-delgallo/php-mini-mvc.git
cd php-mini-mvc
composer install
```

### 2. Configuração do Ambiente

Renomeie `.env.example` para `.env` e configure conforme necessário.

---

## 🌍 Uso de Idiomas

**Exemplo de arquivo:** `languages/system/pt-br.json`

```json
{
  "error.load": "Erro ao carregar: {error}"
}
```

**Uso com substituição:**

```php
lg("system.error.load", ["error" => $e->getMessage()]);
```

Os prefixos de chave são aplicados automaticamente com base na estrutura das pastas.

---

## 🔎 Exemplos de Rotas

**Rota básica:**

```php
$router->get('/', [HomeController::class, 'index']);
```

**Com parâmetros:**

```php
$router->get('/user/{id}', [UserController::class, 'show']);
```

**Com middleware:**

```php
$router->get('/dashboard', [DashboardController::class, 'index'])
       ->middleware([AuthMiddleware::class]);
```

**Exemplo de middleware:**

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

## 📂 Helper de Caminho

`path_base()` é recomendado para gerar URLs de assets locais:

```php
<script src="<?= path_base() ?>/assets/js/app.js"></script>
<link rel="stylesheet" href="<?= path_base() ?>/assets/css/style.css">
```

Garante caminhos corretos mesmo quando o app está hospedado em um subdiretório.

---

## 📊 Uso do Banco de Dados

**Selecionar todos:**

```php
$usuarios = Database::select("SELECT * FROM users");
```

**Selecionar uma linha:**

```php
$usuario = Database::selectRow("SELECT * FROM users WHERE id = ?", [$id]);
```

**Inserir:**

```php
Database::statement("INSERT INTO users (name, email) VALUES (?, ?)", [$nome, $email]);
```

**Com transações:**

```php
Database::startTransaction();
try {
    Database::statement("UPDATE accounts SET balance = balance - 100 WHERE id = ?", [$origem]);
    Database::statement("UPDATE accounts SET balance = balance + 100 WHERE id = ?", [$destino]);
    Database::commitTransaction();
} catch (Exception $e) {
    Database::rollbackTransaction();
}
```

---

## 🤝 Contribuindo

Contribuições são bem-vindas! Você pode:

* Sugerir novos recursos ou melhorias.
* Relatar bugs ou comportamentos inesperados.
* Compartilhar exemplos de como está usando o framework.
* Enviar pull requests com melhorias ou correções.

Para contribuir, abra uma issue ou pull request no [repositório GitHub](https://github.com/vitor-delgallo/php-mini-mvc).

---

## 📜 Licença

Este projeto está licenciado sob a [Licença MIT](LICENSE).
