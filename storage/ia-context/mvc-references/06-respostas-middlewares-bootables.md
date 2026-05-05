# Respostas, middlewares e bootables

## Respostas HTTP

Classe principal:

```php
System\Core\Response
```

Helpers:

```php
response_redirect($uri = '', $method = 'auto', $code = null);
response_html($html, $status = 200);
response_text($text, $status = 200);
response_json($data, $status = 200);
response_xml($xml, $status = 200);
response_file($filePath, $downloadName, $hashFile, $contentType = 'application/octet-stream');
```

Exemplos:

```php
return response_html(view_render_page('home'));

return response_json([
    'status' => 'ok',
]);

return response_redirect('/login');
```

`response_redirect('/login')` transforma caminhos relativos em URL absoluta usando `Path::siteURL()`.

## Middlewares

Middlewares ficam em:

```text
app/Middlewares
```

Exemplo:

```php
namespace App\Middlewares;

use Psr\Http\Message\ServerRequestInterface;

class Auth
{
    public function handle(ServerRequestInterface $request, \Closure $next)
    {
        if (!session_has('user_id')) {
            return response_redirect('/login');
        }

        return $next($request);
    }
}
```

Recomendacoes:

- mantenha middlewares pequenos;
- evite middlewares globais pesados;
- use middlewares por rota ou grupo de rota;
- nao use sessao em rotas API.

## Bootables

Classes bootaveis ficam em:

```text
app/Bootable
```

Devem implementar:

```php
System\Interfaces\IBootable
```

Contrato:

```php
namespace System\Interfaces;

interface IBootable
{
    public static function boot(): void;
}
```

Exemplo:

```php
namespace App\Bootable;

use System\Interfaces\IBootable;
use System\Core\View;

class ShareDefaults implements IBootable
{
    public static function boot(): void
    {
        View::share('appName', 'Minha App');
    }
}
```

Regras:

- bootables rodam em toda requisicao;
- mantenha bootables leves;
- evite consultas globais;
- evite logica pesada;
- use para configuracoes pequenas, compartilhamento de variaveis e inicializacoes simples.
