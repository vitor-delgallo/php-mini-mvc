# Referencia de helpers

Os helpers internos sao carregados automaticamente antes das rotas. Eles sao a API curta recomendada para a aplicacao.

## Funcoes por area

| Area | Helpers |
| --- | --- |
| Banco config | `database_driver`, `database_is`, `database_is_mysql`, `database_is_postgres`, `database_is_none` |
| Banco core | `database_connect`, `database_select`, `database_select_row`, `database_statement`, `database_get_last_inserted_id`, `database_is_in_transaction`, `database_start_transaction`, `database_commit_transaction`, `database_rollback_transaction`, `database_disconnect` |
| Ambiente | `environment_type`, `environment_is`, `environment_is_production`, `environment_is_development`, `environment_is_testing` |
| Form | `form_validator`, `form_validator_register_rule` |
| Globals | `globals_get`, `globals_add`, `globals_merge`, `globals_forget`, `globals_forget_many`, `globals_reset`, `globals_load_env`, `globals_env`, `globals_get_api_prefix`, `globals_is_api_request` |
| Idiomas | `lg`, `language_get`, `language_load`, `ld`, `language_detect`, `language_current`, `language_default` |
| Path | `path_root`, `path_app`, `path_app_bootable`, `path_app_helpers`, `path_app_routes`, `path_app_middlewares`, `path_app_controllers`, `path_app_models`, `path_app_views`, `path_app_views_pages`, `path_app_views_templates`, `path_system`, `path_system_interfaces`, `path_system_helpers`, `path_system_includes`, `path_public`, `path_storage`, `path_storage_sessions`, `path_storage_logs`, `path_languages`, `path_base`, `path_base_public`, `site_url` |
| Response | `response_redirect`, `response_html`, `response_text`, `response_json`, `response_xml`, `response_file` |
| Sessao | `session_start_safe`, `session_has`, `session_get`, `session_set`, `session_set_many`, `session_forget`, `session_clear`, `session_save`, `session_destroy_safe`, `session_regenerate`, `session_driver`, `session_is`, `session_is_files`, `session_is_db`, `session_is_none` |
| View | `view_share`, `view_share_many`, `view_forget`, `view_forget_many`, `view_set_template`, `view_get_template`, `view_render_page`, `view_render_html`, `view_globals` |

## Decisoes rapidas

| Necessidade | Use |
| --- | --- |
| Renderizar pagina | `view_render_page()` + `response_html()` |
| Retornar JSON | `response_json()` |
| Redirecionar | `response_redirect()` |
| Gerar URL absoluta | `site_url()` |
| Gerar caminho de asset | `path_base_public()` |
| Buscar traducao | `lg()` |
| Consultar varias linhas | `database_select()` |
| Consultar uma linha | `database_select_row()` |
| Executar insert/update/delete | `database_statement()` |
| Validar formulario | `form_validator()` |
| Ler sessao web | `session_get()` / `session_has()` |
| Compartilhar variavel global com views | `view_share()` |
| Inicializacao leve global | Classe em `app/Bootable` implementando `IBootable` |

## Orientacao de uso

- Prefira helpers nas views, controllers e middlewares quando eles ja existem.
- Use classes de core quando precisar de comportamento mais especifico que o helper nao expõe.
- Evite duplicar helper no app se o mesmo comportamento ja existir em `system/helpers`.
- Antes de adicionar dependencia, verifique se os helpers e o core resolvem o caso.
