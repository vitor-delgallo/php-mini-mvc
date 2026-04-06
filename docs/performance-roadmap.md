# Análise técnica e roadmap de performance (mini-mvc)

Este documento prioriza ganhos de desempenho **sem adicionar peso desnecessário** ao projeto.

## O que já está bom (manter)

- Arquitetura simples com PSR-4 e bootstrap direto.
- Dependências enxutas (`phprouter`, `diactoros`, `dotenv`).
- Banco com PDO singleton e prepared statements.
- Sistema de idioma por arquivos JSON com fallback.

## Correções rápidas aplicadas agora `[concluído]`

1. `Database::statement()` agora retorna o resultado real do `execute()`, evitando retorno `null` em método tipado como `bool`.
2. `Session::save()` não reabre mais a sessão após `session_write_close()`, evitando lock de sessão desnecessário.
3. Helpers de idioma (`language_detect/current/default`) passaram a retornar `?string`, alinhando tipagem ao núcleo e evitando inconsistência de tipos.

## Próximas implementações recomendadas (baixo custo)

### 1) Cache de descoberta de idiomas por request `[concluído]`

**Problema:** `Language::findLangFiles()` varre recursivamente `/languages` sempre que um idioma é carregado.

**Implementação aplicada:** cache estático em memória por idioma durante o request (sem escrita em disco), evitando varredura recursiva repetida em carregamentos sucessivos.

**Ganho esperado:** menor I/O em requisições com múltiplas chamadas a `Language::load()`.

### 2) Modo de bootstrap “lean” para APIs `[concluído]`

**Problema:** parte do bootstrap de páginas também roda para APIs.

**Implementação aplicada:** fluxo de API com sessão desabilitada via `NULLHandler` e carregamento de rotas focado apenas no arquivo de API, mantendo o autoload global de helpers por compatibilidade.

**Ganho esperado:** latência menor nas rotas de API.

### 3) Flag opcional para auto-load de helpers de app `[concluído]`

**Problema:** carregar todos os helpers da app em toda requisição pode escalar mal.

**Implementação aplicada:** variável de ambiente `APP_HELPERS_AUTOLOAD` para controlar o carregamento dos helpers da app:
- `true` carrega todos;
- `['helper_a','helper_b.php']` carrega apenas os helpers listados (com ou sem `.php`).

**Ganho esperado:** menos includes em produção quando a base crescer.

### 4) Microbenchmark de bootstrap

**Problema:** falta baseline de tempo/memória.

**Sugestão leve:** script simples que mede tempo e memória do bootstrap para comparar antes/depois.

**Ganho esperado:** decisões de performance baseadas em dados.

## Melhorias organizacionais (sem overhead)

### 1) Separar documentação de API pública x interna

- `README`: visão geral e quick start.
- `docs/api-public.md`: helpers e classes recomendadas para uso.
- `docs/internal.md`: detalhes de bootstrap e internals.

Isso reduz atrito para quem vai usar vs. quem vai manter o core.

### 2) Criar checklist de “mudança leve” para PR

Checklist curto:

- adiciona dependência nova? (justificar)
- adiciona I/O em toda request?
- adiciona reflexão/scaneamento global?
- impacto em memória/latência medido?

### 3) Estruturar prioridades por fase

- **Fase 1 (rápida):** correções de tipo/lock/session + microbenchmark.
- **Fase 2 (curta):** cache em memória de language scan + bootstrap lean API.
- **Fase 3 (evolutiva):** otimizações opcionais guiadas por benchmark.

## Correções futuras úteis (sem complexidade)

1. Revisar consistência de nomes de métodos públicos (`render_page`/`render_html`) vs documentação (`renderPage`/`renderHtml`) para evitar confusão de API.
2. Validar caminhos em `View` para mensagens de erro mais claras quando template/página não existir.
3. Adicionar testes mínimos de fumaça para `Language`, `Session` e `Database` (1 happy path por classe).

## Estratégia recomendada

Manter o framework no princípio atual:

- **pouca mágica**
- **baixo acoplamento**
- **I/O previsível por request**
- **otimização guiada por métrica simples**

Assim você preserva o objetivo principal: ser mini, rápido e prático para landing pages e sistemas pequenos.
