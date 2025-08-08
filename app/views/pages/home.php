<!-- Introductory section describing the framework -->
<section>
    <p><strong><?= lg("template.framework.name") ?></strong> <?= lg("doc.body.details") ?></p>
    <ul>
        <li>✅ <?= lg("doc.features.routes") ?></li>
        <li>✅ <?= lg("doc.features.templates") ?></li>
        <li>✅ <?= lg("doc.features.helpers") ?></li>
        <li>✅ <?= lg("doc.features.simple") ?></li>
    </ul>
    <p><?= lg("doc.description.purpose") ?></p>
    <p><?= lg("doc.create.landingpage") ?></p>
</section>

<?php
// Documentation definitions for each class and its methods.
$docs = [
    'System\\Config\\Database' => [
        [
            'name'    => 'env()',
            'code'    => "use System\\Config\\Database;\n\n\$driver = Database::env();",
            'comment' => 'doc.database.code.comment.env',
            'alt'    => "database_driver();",
            'desc'    => 'doc.database.env.desc'
        ],
        [
            'name'    => 'is(string $env)',
            'code'    => "use System\\Config\\Database;\n\nif (Database::is('mysql')) {\n    // your logic\n}",
            'comment' => 'doc.database.code.comment.is',
            'alt'    => "database_is('mysql');",
            'desc'    => 'doc.database.is.desc'
        ],
        [
            'name'    => 'isMysql()',
            'code'    => "use System\\Config\\Database;\n\nif (Database::isMysql()) {\n    // your logic\n}",
            'comment' => 'doc.database.code.comment.ismysql',
            'alt'    => "database_is_mysql();",
            'desc'    => 'doc.database.ismysql.desc'
        ],
        [
            'name'    => 'isPostgres()',
            'code'    => "use System\\Config\\Database;\n\nif (Database::isPostgres()) {\n    // your logic\n}",
            'comment' => 'doc.database.code.comment.ispostgres',
            'alt'    => "database_is_postgres();",
            'desc'    => 'doc.database.ispostgres.desc'
        ],
        [
            'name'    => 'isNone()',
            'code'    => "use System\\Config\\Database;\n\nif (Database::isNone()) {\n    // your logic\n}",
            'comment' => 'doc.database.code.comment.isnone',
            'alt'    => "database_is_none();",
            'desc'    => 'doc.database.isnone.desc'
        ],
    ],
    'System\\Config\\Session' => [
        [
            'name'    => 'env()',
            'code'    => "use System\\Config\\Session;\n\n\$driver = Session::env();",
            'comment' => 'doc.session.code.comment.env',
            'alt'    => "session_driver();",
            'desc'    => 'doc.session.env.desc'
        ],
        [
            'name'    => "is(string \$env)",
            'code'    => "use System\\Config\\Session;\n\nif (Session::is('db')) {\n    // your logic\n}",
            'comment' => 'doc.session.code.comment.is',
            'alt'    => "session_is('db');",
            'desc'    => 'doc.session.is.desc'
        ],
        [
            'name'    => 'isFiles()',
            'code'    => "use System\\Config\\Session;\n\nif (Session::isFiles()) {\n    // your logic\n}",
            'comment' => 'doc.session.code.comment.isfiles',
            'alt'    => "session_is_files();",
            'desc'    => 'doc.session.isfiles.desc'
        ],
        [
            'name'    => 'isDB()',
            'code'    => "use System\\Config\\Session;\n\nif (Session::isDB()) {\n    // your logic\n}",
            'comment' => 'doc.session.code.comment.isdb',
            'alt'    => "session_is_db();",
            'desc'    => 'doc.session.isdb.desc'
        ],
        [
            'name'    => 'isNone()',
            'code'    => "use System\\Config\\Session;\n\nif (Session::isNone()) {\n    // your logic\n}",
            'comment' => 'doc.session.code.comment.isnone',
            'alt'    => "session_is_none();",
            'desc'    => 'doc.session.isnone.desc'
        ],
    ],
    'System\\Config\\Environment' => [
        [
            'name'    => 'env()',
            'code'    => "use System\\Config\\Environment as ConfigEnvironment;\n\n\$current = ConfigEnvironment::env();",
            'comment' => 'doc.environment.code.comment.env',
            'alt'    => "environment_type();",
            'desc'    => 'doc.environment.env.desc'
        ],
        [
            'name'    => "is(string \$env)",
            'code'    => "use System\\Config\\Environment as ConfigEnvironment;\n\nif (ConfigEnvironment::is('development')) {\n    // your logic\n}",
            'comment' => 'doc.environment.code.comment.is',
            'alt'    => "environment_is('development');",
            'desc'    => 'doc.environment.is.desc'
        ],
        [
            'name'    => 'isProduction()',
            'code'    => "use System\\Config\\Environment as ConfigEnvironment;\n\nif (ConfigEnvironment::isProduction()) {\n    // your logic\n}",
            'comment' => 'doc.environment.code.comment.isproduction',
            'alt'    => "environment_is_production();",
            'desc'    => 'doc.environment.isproduction.desc'
        ],
        [
            'name'    => 'isDevelopment()',
            'code'    => "use System\\Config\\Environment as ConfigEnvironment;\n\nif (ConfigEnvironment::isDevelopment()) {\n    // your logic\n}",
            'comment' => 'doc.environment.code.comment.isdevelopment',
            'alt'    => "environment_is_development();",
            'desc'    => 'doc.environment.isdevelopment.desc'
        ],
        [
            'name'    => 'isTesting()',
            'code'    => "use System\\Config\\Environment as ConfigEnvironment;\n\nif (ConfigEnvironment::isTesting()) {\n    // your logic\n}",
            'comment' => 'doc.environment.code.comment.istesting',
            'alt'    => "environment_is_testing();",
            'desc'    => 'doc.environment.istesting.desc'
        ],
    ],
    'System\\Config\\Globals' => [
        [
            'name'    => 'get(?string $key = null)',
            'code'    => "use System\\Config\\Globals;\n\n\$timezone = Globals::get('CACHE_PREFIX');",
            'comment' => 'doc.globals.code.comment.get',
            'alt'    => "globals_get('CACHE_PREFIX');",
            'desc'    => 'doc.globals.get.desc'
        ],
        [
            'name'    => 'add(string $key, mixed $value)',
            'code'    => "use System\\Config\\Globals;\n\nGlobals::add('CACHE_ENABLED', true);",
            'comment' => 'doc.globals.code.comment.add',
            'alt'    => "globals_add('CACHE_ENABLED', true);",
            'desc'    => 'doc.globals.add.desc'
        ],
        [
            'name'    => 'merge(array $config)',
            'code'    => "use System\\Config\\Globals;\n\nGlobals::merge([\n    'CACHE_ENABLED' => true,\n    'APP_MODE' => 'fast',\n]);",
            'comment' => '',
            'alt'    => "globals_merge([\n    'CACHE_ENABLED' => true,\n    'APP_MODE' => 'fast',\n]);",
            'desc'    => 'doc.globals.merge.desc'
        ],
        [
            'name'    => 'forget(string $key)',
            'code'    => "use System\\Config\\Globals;\n\nGlobals::forget('APP_MODE');",
            'comment' => 'doc.globals.code.comment.forget',
            'alt'    => "globals_forget('APP_MODE');",
            'desc'    => 'doc.globals.forget.desc'
        ],
        [
            'name'    => 'forgetMany(array $keys)',
            'code'    => "use System\\Config\\Globals;\n\nGlobals::forgetMany(['APP_MODE', 'CACHE_ENABLED']);",
            'comment' => '',
            'alt'    => "globals_forget_many(['APP_MODE', 'CACHE_ENABLED']);",
            'desc'    => 'doc.globals.forgetmany.desc'
        ],
        [
            'name'    => 'reset()',
            'code'    => "use System\\Config\\Globals;\n\nGlobals::reset();",
            'comment' => 'doc.globals.code.comment.reset',
            'alt'    => "globals_reset();",
            'desc'    => 'doc.globals.reset.desc'
        ],
        [
            'name'    => 'loadEnv()',
            'code'    => "use System\\Config\\Globals;\n\nGlobals::loadEnv();",
            'comment' => 'doc.globals.code.comment.loadenv',
            'alt'    => "globals_load_env();",
            'desc'    => 'doc.globals.loadenv.desc'
        ],
        [
            'name'    => 'env(?string $key = null)',
            'code'    => "use System\\Config\\Globals;\n\n\$dbUser = Globals::env('DB_USER');",
            'comment' => 'doc.globals.code.comment.env',
            'alt'    => "globals_env('DB_USER');",
            'desc'    => 'doc.globals.env.desc'
        ],
    ],
    'System\\Core\\Autoload' => [
        [
            'name'    => 'from(string $directory)',
            'code'    => "use System\\Core\\Autoload;\n\nAutoload::from('/path/to/php/files');",
            'comment' => 'doc.autoload.code.comment.from',
            'alt'    => "autoload_from('/path/to/php/files');",
            'desc'    => 'doc.autoload.from.desc'
        ],
    ],
    'System\\Core\\Database' => [
        [
            'name'    => 'connect()',
            'code'    => "use System\\Core\\Database;\n\n\$pdo = Database::connect();",
            'comment' => 'doc.database.code.comment.connect',
            'alt'    => "\$pdo = database_connect();",
            'desc'    => 'doc.database.connect.desc'
        ],
        [
            'name'    => 'statement(string $sql, array $params = [])',
            'code'    => "use System\\Core\\Database;\n\n\$sql = \"UPDATE users SET name = ? WHERE id = ?\";\nDatabase::statement(\$sql, ['John', 1]);",
            'comment' => 'doc.database.code.comment.statement',
            'alt'    => "database_statement(\$sql, ['John', 1]);",
            'desc'    => 'doc.database.statement.desc'
        ],
        [
            'name'    => 'select(string $sql, array $params = [], ?string $key = null)',
            'code'    => "use System\\Core\\Database;\n\n\$sql = \"SELECT * FROM users\";\n\$users = Database::select(\$sql);",
            'comment' => 'doc.database.code.comment.select',
            'alt'    => "\$users = database_select(\$sql);",
            'desc'    => 'doc.database.select.desc'
        ],
        [
            'name'    => 'selectRow(string $sql, array $params = [], ?string $key = null)',
            'code'    => "use System\\Core\\Database;\n\n\$sql = \"SELECT * FROM users WHERE id = ?\";\n\$user = Database::selectRow(\$sql, [1]);",
            'comment' => 'doc.database.code.comment.selectrow',
            'alt'    => "\$user = database_select_row(\$sql, [1]);",
            'desc'    => 'doc.database.selectrow.desc'
        ],
        [
            'name'    => 'disconnect()',
            'code'    => "use System\\Core\\Database;\n\nDatabase::disconnect();",
            'comment' => 'doc.database.code.comment.disconnect',
            'alt'    => "database_disconnect();",
            'desc'    => 'doc.database.disconnect.desc'
        ],
    ],
    'System\\Core\\Language' => [
        [
            'name'    => 'get(?string $key = null, ?array $replacements = null, ?string $lang = null)',
            'code'    => "use System\\Core\\Language;\n\n\$all = Language::get();\n\$title = Language::get(\"template.framework.name\");",
            'comment' => 'doc.language.code.comment.get.all',
            'alt'    => "\$title = lg(\"template.framework.name\");\n\$title = language_get(\"template.framework.name\");",
            'desc'    => 'doc.language.get.desc'
        ],
        [
            'name'    => 'currentLang()',
            'code'    => "use System\\Core\\Language;\n\n\$lang = Language::currentLang();",
            'comment' => 'doc.language.code.comment.current',
            'alt'    => "\$lang = language_current();",
            'desc'    => 'doc.language.current.desc'
        ],
        [
            'name'    => 'load(?string $lang = null)',
            'code'    => "use System\\Core\\Language;\n\nLanguage::load(\"pt-br\");",
            'comment' => 'doc.language.code.comment.load',
            'alt'    => "language_load(\"pt-br\");\nld(\"pt-br\");",
            'desc'    => 'doc.language.load.desc'
        ],
        [
            'name'    => 'defaultLang()',
            'code'    => "use System\\Core\\Language;\n\n\$lang = Language::defaultLang();",
            'comment' => 'doc.language.code.comment.default',
            'alt'    => "\$lang = language_default();",
            'desc'    => 'doc.language.default.desc'
        ],
        [
            'name'    => 'detect()',
            'code'    => "use System\\Core\\Language;\n\n\$lang = Language::detect();",
            'comment' => 'doc.language.code.comment.detect',
            'alt'    => "\$lang = language_detect();",
            'desc'    => 'doc.language.detect.desc'
        ],
    ],
    'System\\Core\\Path' => [
        [
            'name'    => 'root()',
            'code'    => "use System\\Core\\Path;\n\n\$root = Path::root();",
            'comment' => 'doc.path.code.comment.root',
            'alt'    => "\$root = path_root();",
            'desc'    => 'doc.path.root.desc'
        ],
        [
            'name'    => 'app()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::app();",
            'comment' => 'doc.path.code.comment.app',
            'alt'    => "\$path = path_app();",
            'desc'    => 'doc.path.app.desc'
        ],
        [
            'name'    => 'appBootable()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::appBootable();",
            'comment' => 'doc.path.code.comment.appBootable',
            'alt'    => "\$path = path_app_bootable();",
            'desc'    => 'doc.path.appBootable.desc'
        ],
        [
            'name'    => 'appHelpers()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::appHelpers();",
            'comment' => 'doc.path.code.comment.appHelpers',
            'alt'    => "\$path = path_app_helpers();",
            'desc'    => 'doc.path.appHelpers.desc'
        ],
        [
            'name'    => 'appRoutes()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::appRoutes();",
            'comment' => 'doc.path.code.comment.appRoutes',
            'alt'    => "\$path = path_app_routes();",
            'desc'    => 'doc.path.appRoutes.desc'
        ],
        [
            'name'    => 'appMiddlewares()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::appMiddlewares();",
            'comment' => 'doc.path.code.comment.appMiddlewares',
            'alt'    => "\$path = path_app_middlewares();",
            'desc'    => 'doc.path.appMiddlewares.desc'
        ],
        [
            'name'    => 'appControllers()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::appControllers();",
            'comment' => 'doc.path.code.comment.appControllers',
            'alt'    => "\$path = path_app_controllers();",
            'desc'    => 'doc.path.appControllers.desc'
        ],
        [
            'name'    => 'appModels()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::appModels();",
            'comment' => 'doc.path.code.comment.appModels',
            'alt'    => "\$path = path_app_models();",
            'desc'    => 'doc.path.appModels.desc'
        ],
        [
            'name'    => 'appViews()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::appViews();",
            'comment' => 'doc.path.code.comment.appViews',
            'alt'    => "\$path = path_app_views();",
            'desc'    => 'doc.path.appViews.desc'
        ],
        [
            'name'    => 'appViewsPages()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::appViewsPages();",
            'comment' => 'doc.path.code.comment.appViewsPages',
            'alt'    => "\$path = path_app_views_pages();",
            'desc'    => 'doc.path.appViewsPages.desc'
        ],
        [
            'name'    => 'appViewsTemplates()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::appViewsTemplates();",
            'comment' => 'doc.path.code.comment.appViewsTemplates',
            'alt'    => "\$path = path_app_views_templates();",
            'desc'    => 'doc.path.appViewsTemplates.desc'
        ],
        [
            'name'    => 'system()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::system();",
            'comment' => 'doc.path.code.comment.system',
            'alt'    => "\$path = path_system();",
            'desc'    => 'doc.path.system.desc'
        ],
        [
            'name'    => 'systemInterfaces()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::systemInterfaces();",
            'comment' => 'doc.path.code.comment.systemInterfaces',
            'alt'    => "\$path = path_system_interfaces();",
            'desc'    => 'doc.path.systemInterfaces.desc'
        ],
        [
            'name'    => 'systemHelpers()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::systemHelpers();",
            'comment' => 'doc.path.code.comment.systemHelpers',
            'alt'    => "\$path = path_system_helpers();",
            'desc'    => 'doc.path.systemHelpers.desc'
        ],
        [
            'name'    => 'systemIncludes()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::systemIncludes();",
            'comment' => 'doc.path.code.comment.systemIncludes',
            'alt'    => "\$path = path_system_includes();",
            'desc'    => 'doc.path.systemIncludes.desc'
        ],
        [
            'name'    => 'public()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::public();",
            'comment' => 'doc.path.code.comment.public',
            'alt'    => "\$path = path_public();",
            'desc'    => 'doc.path.public.desc'
        ],
        [
            'name'    => 'storage()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::storage();",
            'comment' => 'doc.path.code.comment.storage',
            'alt'    => "\$path = path_storage();",
            'desc'    => 'doc.path.storage.desc'
        ],
        [
            'name'    => 'storageSessions()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::storageSessions();",
            'comment' => 'doc.path.code.comment.storageSessions',
            'alt'    => "\$path = path_storage_sessions();",
            'desc'    => 'doc.path.storageSessions.desc'
        ],
        [
            'name'    => 'storageLogs()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::storageLogs();",
            'comment' => 'doc.path.code.comment.storageLogs',
            'alt'    => "\$path = path_storage_logs();",
            'desc'    => 'doc.path.storageLogs.desc'
        ],
        [
            'name'    => 'languages()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::languages();",
            'comment' => 'doc.path.code.comment.languages',
            'alt'    => "\$path = path_languages();",
            'desc'    => 'doc.path.languages.desc'
        ],
        [
            'name'    => 'basePath()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::basePath();",
            'comment' => 'doc.path.code.comment.basePath',
            'alt'    => "\$path = path_base();",
            'desc'    => 'doc.path.basePath.desc'
        ],
        [
            'name'    => 'siteURL(?string $final = null)',
            'code'    => "use System\\Core\\Path;\n\n\$url = Path::siteURL('dashboard');",
            'comment' => 'doc.path.code.comment.siteURL',
            'alt'    => "\$url = site_url('dashboard');",
            'desc'    => 'doc.path.siteURL.desc'
        ],
    ],
    'System\\Core\\Response' => [
        [
            'name'    => 'redirect(string $uri = \'\', string $method = \'auto\', ?int $code = null)',
            'code'    => "use System\\Core\\Response;\n\nreturn Response::redirect('/login');",
            'comment' => 'doc.response.code.comment.redirect',
            'alt'    => "return response_redirect('/login');",
            'desc'    => 'doc.response.redirect.desc'
        ],
        [
            'name'    => 'html(string $html, int $status = 200)',
            'code'    => "use System\\Core\\Response;\n\nreturn Response::html('&lt;h1&gt;Hello&lt;/h1&gt;');",
            'comment' => 'doc.response.code.comment.html',
            'alt'    => "return response_html('&lt;h1&gt;Hello&lt;/h1&gt;');",
            'desc'    => 'doc.response.html.desc'
        ],
        [
            'name'    => 'json(array|string $data, int $status = 200)',
            'code'    => "use System\\Core\\Response;\n\nreturn Response::json(['status' => 'ok']);",
            'comment' => 'doc.response.code.comment.json',
            'alt'    => "return response_json(['status' => 'ok']);",
            'desc'    => 'doc.response.json.desc'
        ],
        [
            'name'    => 'xml(string $xml, int $status = 200)',
            'code'    => "use System\\Core\\Response;\n\nreturn Response::xml('&lt;message&gt;Hello&lt;/message&gt;');",
            'comment' => 'doc.response.code.comment.xml',
            'alt'    => "return response_xml('&lt;message&gt;Hello&lt;/message&gt;');",
            'desc'    => 'doc.response.xml.desc'
        ],
    ],
    'System\\Core\\Session' => [
        [
            'name'    => 'start()',
            'code'    => "use System\\Core\\Session;\n\nSession::start();",
            'comment' => 'doc.session.code.comment.start',
            'alt'    => "session_start_safe();",
            'desc'    => 'doc.session.start.desc'
        ],
        [
            'name'    => 'has(string $key)',
            'code'    => "use System\\Core\\Session;\n\nif (Session::has('user_id')) {\n    // your logic\n}",
            'comment' => 'doc.session.code.comment.has',
            'alt'    => "session_has('user_id');",
            'desc'    => 'doc.session.has.desc'
        ],
        [
            'name'    => 'get(string $key, mixed $default = null)',
            'code'    => "use System\\Core\\Session;\n\n\$userId = Session::get('user_id', 0);",
            'comment' => 'doc.session.code.comment.get',
            'alt'    => "\$userId = session_get('user_id', 0);",
            'desc'    => 'doc.session.get.desc'
        ],
        [
            'name'    => 'set(string $key, mixed $value)',
            'code'    => "use System\\Core\\Session;\n\nSession::set('user_id', 123);",
            'comment' => 'doc.session.code.comment.set',
            'alt'    => "session_set('user_id', 123);",
            'desc'    => 'doc.session.set.desc'
        ],
        [
            'name'    => 'setMany(array $items)',
            'code'    => "use System\\Core\\Session;\n\nSession::setMany([\n  'user_id' => 123,\n  'user_role' => 'admin'\n]);",
            'comment' => 'doc.session.code.comment.setMany',
            'alt'    => "session_set_many([\n  'user_id' => 123,\n  'user_role' => 'admin'\n]);",
            'desc'    => 'doc.session.setMany.desc'
        ],
        [
            'name'    => 'forget(string $key)',
            'code'    => "use System\\Core\\Session;\n\nSession::forget('user_id');",
            'comment' => 'doc.session.code.comment.forget',
            'alt'    => "session_forget('user_id');",
            'desc'    => 'doc.session.forget.desc'
        ],
        [
            'name'    => 'clear()',
            'code'    => "use System\\Core\\Session;\n\nSession::clear();",
            'comment' => 'doc.session.code.comment.clear',
            'alt'    => "session_clear();",
            'desc'    => 'doc.session.clear.desc'
        ],
        [
            'name'    => 'save()',
            'code'    => "use System\\Core\\Session;\n\nSession::save();",
            'comment' => 'doc.session.code.comment.save',
            'alt'    => "session_save();",
            'desc'    => 'doc.session.save.desc'
        ],
        [
            'name'    => 'destroy()',
            'code'    => "use System\\Core\\Session;\n\nSession::destroy();",
            'comment' => 'doc.session.code.comment.destroy',
            'alt'    => "session_destroy_safe();",
            'desc'    => 'doc.session.destroy.desc'
        ],
        [
            'name'    => 'regenerate(bool $deleteOldSession = true)',
            'code'    => "use System\\Core\\Session;\n\nSession::regenerate();",
            'comment' => 'doc.session.code.comment.regenerate',
            'alt'    => "session_regenerate();",
            'desc'    => 'doc.session.regenerate.desc'
        ],
    ],
    'System\\Core\\View' => [
        [
            'name'    => 'share(string $key, mixed $value)',
            'code'    => "use System\\Core\\View;\n\nView::share('app_name', 'Mini ERP');",
            'comment' => 'doc.view.code.comment.share',
            'alt'    => "view_share('app_name', 'Mini ERP');",
            'desc'    => 'doc.view.share.desc'
        ],
        [
            'name'    => 'shareMany(array $items)',
            'code'    => "use System\\Core\\View;\n\nView::shareMany([\n  'user' => \$user,\n  'language' => 'pt-BR'\n]);",
            'comment' => 'doc.view.code.comment.shareMany',
            'alt'    => "view_share_many([\n  'user' => \$user,\n  'language' => 'pt-BR'\n]);",
            'desc'    => 'doc.view.shareMany.desc'
        ],
        [
            'name'    => 'forget(string $key)',
            'code'    => "use System\\Core\\View;\n\nView::forget('language');",
            'comment' => 'doc.view.code.comment.forget',
            'alt'    => "view_forget('language');",
            'desc'    => 'doc.view.forget.desc'
        ],
        [
            'name'    => 'forgetMany(array $keys)',
            'code'    => "use System\\Core\\View;\n\nView::forgetMany(['user', 'language']);",
            'comment' => 'doc.view.code.comment.forgetMany',
            'alt'    => "view_forget_many(['user', 'language']);",
            'desc'    => 'doc.view.forgetMany.desc'
        ],
        [
            'name'    => 'clear()',
            'code'    => "use System\\Core\\View;\n\nView::clear();",
            'comment' => 'doc.view.code.comment.clear',
            'alt'    => "view_clear();",
            'desc'    => 'doc.view.clear.desc'
        ],
        [
            'name'    => 'setTemplate(?string $relativePath = null)',
            'code'    => "use System\\Core\\View;\n\nView::setTemplate('layouts/main');",
            'comment' => 'doc.view.code.comment.setTemplate',
            'alt'    => "view_set_template('layouts/main');",
            'desc'    => 'doc.view.setTemplate.desc'
        ],
        [
            'name'    => 'getTemplate()',
            'code'    => "use System\\Core\\View;\n\n\$template = View::getTemplate();",
            'comment' => 'doc.view.code.comment.getTemplate',
            'alt'    => "\$template = view_get_template();",
            'desc'    => 'doc.view.getTemplate.desc'
        ],
        [
            'name'    => 'render_page(string $page, array $data = [])',
            'code'    => "use System\\Core\\View;\n\necho View::render_page('home', ['user' => \$user]);",
            'comment' => 'doc.view.code.comment.renderPage',
            'alt'    => "echo view_render_page('home', ['user' => \$user]);",
            'desc'    => 'doc.view.renderPage.desc'
        ],
        [
            'name'    => 'render_html(string $html, array $data = [])',
            'code'    => "use System\\Core\\View;\n\necho View::render_html('&lt;h1&gt;Olá&lt;/h1&gt;');",
            'comment' => 'doc.view.code.comment.renderHtml',
            'alt'    => "echo view_render_html('&lt;h1&gt;Olá&lt;/h1&gt;');",
            'desc'    => 'doc.view.renderHtml.desc'
        ],
        [
            'name'    => 'getGlobals()',
            'code'    => "use System\\Core\\View;\n\n\$globals = View::getGlobals();",
            'comment' => 'doc.view.code.comment.getGlobals',
            'alt'    => "\$globals = view_globals();",
            'desc'    => 'doc.view.getGlobals.desc'
        ],
    ],
    'System\\Core\\FormValidator' => [
        [
            'name'    => 'validate(array $rules)',
            'code'    => "use System\\Core\\FormValidator;\n\n\$form = new FormValidator();\n\$form->setForm(\$_POST);\n\$isValid = \$form->validate([\n    'email' => 'required|email',\n    'password' => 'required|min:8'\n]);\n\nif (!\$isValid) {\n    \$errors = \$form->errors();\n}",
            'comment' => '',
            'alt'    => "\$form = form_validator(\$_POST);\n\nif (!\$form->validate([\n    'email' => 'required|email',\n    'password' => 'required|min:8'\n])) {\n    \$errors = \$form->errors();\n}",
            'desc'    => 'doc.form_validator.validate.desc'
        ],
        [
            'name'    => 'registerRule(string $name, callable $callback)',
            'code'    => "use System\\Core\\FormValidator;\n\n// Using the class directly\nFormValidator::registerRule('cpf', function(\$value) {\n    return preg_match('/^\\d{11}$/', \$value)\n        ? true\n        : lg('doc.form_validator.code.comment.cpf.error');\n});",
            'comment' => '',
            'alt'    => "form_validator_register_rule('cpf', function(\$value) {\n    return preg_match('/^\\d{11}$/', \$value)\n        ? true\n        : lg('doc.form_validator.code.comment.cpf.error');\n});",
            'desc'    => 'doc.form_validator.register_rule.desc'
        ],
        [
            'name'    => 'setForm(array $data)',
            'code'    => "use System\\Core\\FormValidator;\n\n\$form = new FormValidator();\n\$form->setForm(['name' => 'Vitor', 'email' => 'vitor@email.com']);",
            'comment' => '',
            'alt'    => "form_validator(['name' => 'Vitor', 'email' => 'vitor@email.com']);",
            'desc'    => 'doc.form_validator.setForm.desc'
        ],
        [
            'name'    => 'resetErrors()',
            'code'    => "use System\\Core\\FormValidator;\n\n\$form = new FormValidator();\n\$form->resetErrors();",
            'comment' => '',
            'alt'    => "form_validator()->resetErrors();",
            'desc'    => 'doc.form_validator.resetErrors.desc'
        ],
        [
            'name'    => 'errors()',
            'code'    => "use System\\Core\\FormValidator;\n\n\$form = new FormValidator();\n\$errors = \$form->errors();",
            'comment' => '',
            'alt'    => "\$errors = form_validator()->errors();",
            'desc'    => 'doc.form_validator.errors.desc'
        ],
        [
            'name'    => 'get(string $key, mixed $default = null)',
            'code'    => "use System\\Core\\FormValidator;\n\n\$form = new FormValidator();\n\$form->setForm(['age' => 25]);\n\$age = \$form->get('age');",
            'comment' => 'doc.form_validator.code.comment.get',
            'alt'    => "\$age = form_validator(['age' => 25])->get('age');",
            'desc'    => 'doc.form_validator.get.desc'
        ],
        [
            'name'    => 'has(string $key)',
            'code'    => "use System\\Core\\FormValidator;\n\n\$form = new FormValidator();\n\$form->setForm(['token' => 'abc']);\nif (\$form->has('token')) {\n    // your logic\n}",
            'comment' => 'doc.form_validator.code.comment.has',
            'alt'    => "if (form_validator(['token' => 'abc'])->has('token')) {\n    // your logic\n}",
            'desc'    => 'doc.form_validator.has.desc'
        ],
    ],
];
?>

<?php foreach ($docs as $class => $methods): ?>
<section>
    <details>
        <summary><strong><?= $class ?></strong></summary>
        <?php foreach ($methods as $method): ?>
        <details>
            <summary><?= $method['name'] ?></summary>
            <pre><code>
<?= $method['code'] ?><?php if (!empty($method['comment'])): ?> // <?= lg($method['comment']) ?><?php endif; ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code><?= $method['alt'] ?></code></pre>
            <footer><em><?= lg($method['desc']) ?></em></footer>
        </details>
        <?php endforeach; ?>
    </details>
</section>
<?php endforeach; ?>