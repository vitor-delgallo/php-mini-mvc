<!-- Introductory section describing the framework -->
<section>
    <p><strong><?= lg("system.template.framework.name") ?></strong> <?= lg("system.doc.body.details") ?></p>
    <ul>
        <li>✅ <?= lg("system.doc.features.routes") ?></li>
        <li>✅ <?= lg("system.doc.features.templates") ?></li>
        <li>✅ <?= lg("system.doc.features.helpers") ?></li>
        <li>✅ <?= lg("system.doc.features.simple") ?></li>
    </ul>
    <p><?= lg("system.doc.description.purpose") ?></p>
    <p><?= lg("system.doc.create.landingpage") ?></p>
</section>

<?php
// Documentation definitions for each class and its methods.
$docs = [
    'System\\Config\\Database' => [
        [
            'name'    => 'env()',
            'code'    => "use System\\Config\\Database;\n\n\$driver = Database::env();",
            'comment' => 'system.doc.database.code.comment.env',
            'alt'    => "database_driver();",
            'desc'    => 'system.doc.database.env.desc'
        ],
        [
            'name'    => 'is(string $env)',
            'code'    => "use System\\Config\\Database;\n\nif (Database::is('mysql')) {\n    // your logic\n}",
            'comment' => 'system.doc.database.code.comment.is',
            'alt'    => "database_is('mysql');",
            'desc'    => 'system.doc.database.is.desc'
        ],
        [
            'name'    => 'isMysql()',
            'code'    => "use System\\Config\\Database;\n\nif (Database::isMysql()) {\n    // your logic\n}",
            'comment' => 'system.doc.database.code.comment.ismysql',
            'alt'    => "database_is_mysql();",
            'desc'    => 'system.doc.database.ismysql.desc'
        ],
        [
            'name'    => 'isPostgres()',
            'code'    => "use System\\Config\\Database;\n\nif (Database::isPostgres()) {\n    // your logic\n}",
            'comment' => 'system.doc.database.code.comment.ispostgres',
            'alt'    => "database_is_postgres();",
            'desc'    => 'system.doc.database.ispostgres.desc'
        ],
        [
            'name'    => 'isNone()',
            'code'    => "use System\\Config\\Database;\n\nif (Database::isNone()) {\n    // your logic\n}",
            'comment' => 'system.doc.database.code.comment.isnone',
            'alt'    => "database_is_none();",
            'desc'    => 'system.doc.database.isnone.desc'
        ],
        [
            'name'    => "permittedDrivers()",
            'code'    => "use System\\Config\\Database;\n\n\$drivers = Database::permittedDrivers();",
            'comment' => 'system.doc.database.code.comment.permitteddrivers',
            'alt'    => "\$drivers = database_config_permitted_drivers();",
            'desc'    => 'system.doc.database.permitteddrivers.desc'
        ],
        [
            'name'    => "normalizeConnectionName(?string \$connection = null)",
            'code'    => "use System\\Config\\Database;\n\n\$connection = Database::normalizeConnectionName('AUTH');",
            'comment' => 'system.doc.database.code.comment.normalizeconnectionname',
            'alt'    => "\$connection = database_config_normalize_connection_name('AUTH');",
            'desc'    => 'system.doc.database.normalizeconnectionname.desc'
        ],
        [
            'name'    => "defaultPort(string \$driver)",
            'code'    => "use System\\Config\\Database;\n\n\$port = Database::defaultPort('mysql');",
            'comment' => 'system.doc.database.code.comment.defaultport',
            'alt'    => "\$port = database_config_default_port('mysql');",
            'desc'    => 'system.doc.database.defaultport.desc'
        ],
        [
            'name'    => "connection(string \$connection = 'default')",
            'code'    => "use System\\Config\\Database;\n\n\$config = Database::connection('auth');",
            'comment' => 'system.doc.database.code.comment.connectionconfig',
            'alt'    => "\$config = database_config_connection('auth');",
            'desc'    => 'system.doc.database.connectionconfig.desc'
        ],
        [
            'name'    => "connections()",
            'code'    => "use System\\Config\\Database;\n\n\$connections = Database::connections();",
            'comment' => 'system.doc.database.code.comment.connectionsconfig',
            'alt'    => "\$connections = database_config_connections();",
            'desc'    => 'system.doc.database.connectionsconfig.desc'
        ],
        [
            'name'    => "connectionNames()",
            'code'    => "use System\\Config\\Database;\n\n\$connections = Database::connectionNames();",
            'comment' => 'system.doc.database.code.comment.connectionnames',
            'alt'    => "\$connections = database_connection_names();",
            'desc'    => 'system.doc.database.connectionnames.desc'
        ],
        [
            'name'    => "hasConnection(string \$connection)",
            'code'    => "use System\\Config\\Database;\n\nif (Database::hasConnection('auth')) {\n    // auth DB is configured\n}",
            'comment' => 'system.doc.database.code.comment.hasconnection',
            'alt'    => "database_has_connection('auth');",
            'desc'    => 'system.doc.database.hasconnection.desc'
        ],
        [
            'name'    => 'configure(string $connection, array $config)',
            'code'    => "use System\\Config\\Database;\n\nDatabase::configure('reporting', [\n    'driver' => 'mysql',\n    'host' => '127.0.0.1',\n    'name' => 'reports',\n    'user' => 'report_user',\n]);",
            'comment' => 'system.doc.database.code.comment.configconfigure',
            'alt'    => "database_config_configure('reporting', [\n    'driver' => 'mysql',\n    'host' => '127.0.0.1',\n    'name' => 'reports',\n    'user' => 'report_user',\n]);",
            'desc'    => 'system.doc.database.configconfigure.desc'
        ],
        [
            'name'    => 'forgetConnection(string $connection)',
            'code'    => "use System\\Config\\Database;\n\nDatabase::forgetConnection('reporting');",
            'comment' => 'system.doc.database.code.comment.configforgetconnection',
            'alt'    => "database_config_forget_connection('reporting');",
            'desc'    => 'system.doc.database.configforgetconnection.desc'
        ],
    ],
    'System\\Config\\Session' => [
        [
            'name'    => 'env()',
            'code'    => "use System\\Config\\Session;\n\n\$driver = Session::env();",
            'comment' => 'system.doc.session.code.comment.env',
            'alt'    => "session_driver();",
            'desc'    => 'system.doc.session.env.desc'
        ],
        [
            'name'    => "is(string \$env)",
            'code'    => "use System\\Config\\Session;\n\nif (Session::is('db')) {\n    // your logic\n}",
            'comment' => 'system.doc.session.code.comment.is',
            'alt'    => "session_is('db');",
            'desc'    => 'system.doc.session.is.desc'
        ],
        [
            'name'    => 'isFiles()',
            'code'    => "use System\\Config\\Session;\n\nif (Session::isFiles()) {\n    // your logic\n}",
            'comment' => 'system.doc.session.code.comment.isfiles',
            'alt'    => "session_is_files();",
            'desc'    => 'system.doc.session.isfiles.desc'
        ],
        [
            'name'    => 'isDB()',
            'code'    => "use System\\Config\\Session;\n\nif (Session::isDB()) {\n    // your logic\n}",
            'comment' => 'system.doc.session.code.comment.isdb',
            'alt'    => "session_is_db();",
            'desc'    => 'system.doc.session.isdb.desc'
        ],
        [
            'name'    => 'isNone()',
            'code'    => "use System\\Config\\Session;\n\nif (Session::isNone()) {\n    // your logic\n}",
            'comment' => 'system.doc.session.code.comment.isnone',
            'alt'    => "session_is_none();",
            'desc'    => 'system.doc.session.isnone.desc'
        ],
    ],
    'System\\Config\\Environment' => [
        [
            'name'    => 'env()',
            'code'    => "use System\\Config\\Environment as ConfigEnvironment;\n\n\$current = ConfigEnvironment::env();",
            'comment' => 'system.doc.environment.code.comment.env',
            'alt'    => "environment_type();",
            'desc'    => 'system.doc.environment.env.desc'
        ],
        [
            'name'    => "is(string \$env)",
            'code'    => "use System\\Config\\Environment as ConfigEnvironment;\n\nif (ConfigEnvironment::is('development')) {\n    // your logic\n}",
            'comment' => 'system.doc.environment.code.comment.is',
            'alt'    => "environment_is('development');",
            'desc'    => 'system.doc.environment.is.desc'
        ],
        [
            'name'    => 'isProduction()',
            'code'    => "use System\\Config\\Environment as ConfigEnvironment;\n\nif (ConfigEnvironment::isProduction()) {\n    // your logic\n}",
            'comment' => 'system.doc.environment.code.comment.isproduction',
            'alt'    => "environment_is_production();",
            'desc'    => 'system.doc.environment.isproduction.desc'
        ],
        [
            'name'    => 'isDevelopment()',
            'code'    => "use System\\Config\\Environment as ConfigEnvironment;\n\nif (ConfigEnvironment::isDevelopment()) {\n    // your logic\n}",
            'comment' => 'system.doc.environment.code.comment.isdevelopment',
            'alt'    => "environment_is_development();",
            'desc'    => 'system.doc.environment.isdevelopment.desc'
        ],
        [
            'name'    => 'isTesting()',
            'code'    => "use System\\Config\\Environment as ConfigEnvironment;\n\nif (ConfigEnvironment::isTesting()) {\n    // your logic\n}",
            'comment' => 'system.doc.environment.code.comment.istesting',
            'alt'    => "environment_is_testing();",
            'desc'    => 'system.doc.environment.istesting.desc'
        ],
    ],
    'System\\Config\\Globals' => [
        [
            'name'    => 'get(?string $key = null)',
            'code'    => "use System\\Config\\Globals;\n\n\$timezone = Globals::get('CACHE_PREFIX');",
            'comment' => 'system.doc.globals.code.comment.get',
            'alt'    => "globals_get('CACHE_PREFIX');",
            'desc'    => 'system.doc.globals.get.desc'
        ],
        [
            'name'    => 'add(string $key, mixed $value)',
            'code'    => "use System\\Config\\Globals;\n\nGlobals::add('CACHE_ENABLED', true);",
            'comment' => 'system.doc.globals.code.comment.add',
            'alt'    => "globals_add('CACHE_ENABLED', true);",
            'desc'    => 'system.doc.globals.add.desc'
        ],
        [
            'name'    => 'merge(array $config)',
            'code'    => "use System\\Config\\Globals;\n\nGlobals::merge([\n    'CACHE_ENABLED' => true,\n    'APP_MODE' => 'fast',\n]);",
            'comment' => '',
            'alt'    => "globals_merge([\n    'CACHE_ENABLED' => true,\n    'APP_MODE' => 'fast',\n]);",
            'desc'    => 'system.doc.globals.merge.desc'
        ],
        [
            'name'    => 'forget(string $key)',
            'code'    => "use System\\Config\\Globals;\n\nGlobals::forget('APP_MODE');",
            'comment' => 'system.doc.globals.code.comment.forget',
            'alt'    => "globals_forget('APP_MODE');",
            'desc'    => 'system.doc.globals.forget.desc'
        ],
        [
            'name'    => 'forgetMany(array $keys)',
            'code'    => "use System\\Config\\Globals;\n\nGlobals::forgetMany(['APP_MODE', 'CACHE_ENABLED']);",
            'comment' => '',
            'alt'    => "globals_forget_many(['APP_MODE', 'CACHE_ENABLED']);",
            'desc'    => 'system.doc.globals.forgetmany.desc'
        ],
        [
            'name'    => 'reset()',
            'code'    => "use System\\Config\\Globals;\n\nGlobals::reset();",
            'comment' => 'system.doc.globals.code.comment.reset',
            'alt'    => "globals_reset();",
            'desc'    => 'system.doc.globals.reset.desc'
        ],
        [
            'name'    => 'loadEnv()',
            'code'    => "use System\\Config\\Globals;\n\nGlobals::loadEnv();",
            'comment' => 'system.doc.globals.code.comment.loadenv',
            'alt'    => "globals_load_env();",
            'desc'    => 'system.doc.globals.loadenv.desc'
        ],
        [
            'name'    => 'env(?string $key = null)',
            'code'    => "use System\\Config\\Globals;\n\n\$dbUser = Globals::env('DB_USER');",
            'comment' => 'system.doc.globals.code.comment.env',
            'alt'    => "globals_env('DB_USER');",
            'desc'    => 'system.doc.globals.env.desc'
        ],
        [
            'name'    => 'getApiPrefix()',
            'code'    => "use System\\Config\\Globals;\n\n\$apiPrefix = Globals::getApiPrefix();",
            'comment' => 'system.doc.globals.code.comment.getapiprefix',
            'alt'    => "globals_get_api_prefix();",
            'desc'    => 'system.doc.globals.getapiprefix.desc'
        ],
        [
            'name'    => 'isApiRequest()',
            'code'    => "use System\\Config\\Globals;\n\n\$isApiRequest = Globals::isApiRequest();",
            'comment' => 'system.doc.globals.code.comment.isapirequest',
            'alt'    => "globals_is_api_request();",
            'desc'    => 'system.doc.globals.isapirequest.desc'
        ],
        [
            'name'    => 'getSystemWebPrefix()',
            'code'    => "use System\\Config\\Globals;\n\n\$prefix = Globals::getSystemWebPrefix();",
            'comment' => 'system.doc.globals.code.comment.getsystemwebprefix',
            'alt'    => "globals_get_system_web_prefix();",
            'desc'    => 'system.doc.globals.getsystemwebprefix.desc'
        ],
        [
            'name'    => 'getSystemApiPrefix()',
            'code'    => "use System\\Config\\Globals;\n\n\$prefix = Globals::getSystemApiPrefix();",
            'comment' => 'system.doc.globals.code.comment.getsystemapiprefix',
            'alt'    => "globals_get_system_api_prefix();",
            'desc'    => 'system.doc.globals.getsystemapiprefix.desc'
        ],
        [
            'name'    => 'isSystemWebRequest()',
            'code'    => "use System\\Config\\Globals;\n\n\$isSystemWebRequest = Globals::isSystemWebRequest();",
            'comment' => 'system.doc.globals.code.comment.issystemwebrequest',
            'alt'    => "globals_is_system_web_request();",
            'desc'    => 'system.doc.globals.issystemwebrequest.desc'
        ],
        [
            'name'    => 'isSystemApiRequest()',
            'code'    => "use System\\Config\\Globals;\n\n\$isSystemApiRequest = Globals::isSystemApiRequest();",
            'comment' => 'system.doc.globals.code.comment.issystemapirequest',
            'alt'    => "globals_is_system_api_request();",
            'desc'    => 'system.doc.globals.issystemapirequest.desc'
        ],
    ],
    'System\\Core\\PHPAutoload' => [
        [
            'name'    => 'from(string $directory)',
            'code'    => "use System\\Core\\PHPAutoload;\n\nPHPAutoload::from(path_app_helpers());",
            'comment' => 'system.doc.autoload.code.comment.from',
            'alt'    => "php_autoload_from(path_app_helpers());",
            'desc'    => 'system.doc.autoload.from.desc'
        ],
        [
            'name'    => 'boot()',
            'code'    => "use System\\Core\\PHPAutoload;\n\nPHPAutoload::boot();",
            'comment' => 'system.doc.autoload.code.comment.boot',
            'alt'    => "php_autoload_boot();",
            'desc'    => 'system.doc.autoload.boot.desc'
        ],
    ],
    'System\\Core\\RouterLoader' => [
        [
            'name'    => 'load(string $file)',
            'code'    => "use System\\Core\\RouterLoader;\n\nRouterLoader::load('web');",
            'comment' => 'system.doc.router_loader.code.comment.load',
            'alt'    => "router_loader_load('web');",
            'desc'    => 'system.doc.router_loader.load.desc'
        ],
        [
            'name'    => 'loadWithPrefix(string $prefix, string $file)',
            'code'    => "use System\\Core\\RouterLoader;\n\nRouterLoader::loadWithPrefix('/api', 'api');",
            'comment' => 'system.doc.router_loader.code.comment.loadWithPrefix',
            'alt'    => "router_loader_load_with_prefix('/api', 'api');",
            'desc'    => 'system.doc.router_loader.loadWithPrefix.desc'
        ],
        [
            'name'    => 'loadSystem(string $file)',
            'code'    => "use System\\Core\\RouterLoader;\n\nRouterLoader::loadSystem('web');",
            'comment' => 'system.doc.router_loader.code.comment.loadSystem',
            'alt'    => "router_loader_load_system('web');",
            'desc'    => 'system.doc.router_loader.loadSystem.desc'
        ],
        [
            'name'    => 'loadSystemWithPrefix(string $prefix, string $file)',
            'code'    => "use System\\Core\\RouterLoader;\n\nRouterLoader::loadSystemWithPrefix('/web-system', 'web');",
            'comment' => 'system.doc.router_loader.code.comment.loadSystemWithPrefix',
            'alt'    => "router_loader_load_system_with_prefix('/web-system', 'web');",
            'desc'    => 'system.doc.router_loader.loadSystemWithPrefix.desc'
        ],
        [
            'name'    => 'dispatch()',
            'code'    => "use System\\Core\\RouterLoader;\n\nRouterLoader::dispatch();",
            'comment' => 'system.doc.router_loader.code.comment.dispatch',
            'alt'    => "router_loader_dispatch();",
            'desc'    => 'system.doc.router_loader.dispatch.desc'
        ],
    ],
    'System\\Core\\Database' => [
        [
            'name'    => "connect(string \$connection = 'default')",
            'code'    => "use System\\Core\\Database;\n\n\$pdo = Database::connect();\n\$auth = Database::connect('auth');",
            'comment' => 'system.doc.database.code.comment.connect',
            'alt'    => "\$pdo = database_connect();\n\$auth = database_connect('auth');",
            'desc'    => 'system.doc.database.connect.desc'
        ],
        [
            'name'    => 'configure(string $connection, array $config)',
            'code'    => "use System\\Core\\Database;\n\nDatabase::configure('reporting', [\n    'driver' => 'mysql',\n    'host' => '127.0.0.1',\n    'name' => 'reports',\n    'user' => 'report_user',\n]);",
            'comment' => 'system.doc.database.code.comment.configure',
            'alt'    => "database_configure('reporting', [\n    'driver' => 'mysql',\n    'host' => '127.0.0.1',\n    'name' => 'reports',\n    'user' => 'report_user',\n]);",
            'desc'    => 'system.doc.database.configure.desc'
        ],
        [
            'name'    => 'forgetConnection(string $connection)',
            'code'    => "use System\\Core\\Database;\n\nDatabase::forgetConnection('reporting');",
            'comment' => 'system.doc.database.code.comment.forgetconnection',
            'alt'    => "database_forget_connection('reporting');",
            'desc'    => 'system.doc.database.forgetconnection.desc'
        ],
        [
            'name'    => "connectionNames()",
            'code'    => "use System\\Core\\Database;\n\n\$connections = Database::connectionNames();",
            'comment' => 'system.doc.database.code.comment.connectionnames',
            'alt'    => "\$connections = database_connection_names();",
            'desc'    => 'system.doc.database.connectionnames.desc'
        ],
        [
            'name'    => "hasConnection(string \$connection)",
            'code'    => "use System\\Core\\Database;\n\nif (Database::hasConnection('auth')) {\n    // auth DB is configured\n}",
            'comment' => 'system.doc.database.code.comment.hasconnection',
            'alt'    => "database_has_connection('auth');",
            'desc'    => 'system.doc.database.hasconnection.desc'
        ],
        [
            'name'    => "statement(string \$sql, array \$params = [], string \$connection = 'default')",
            'code'    => "use System\\Core\\Database;\n\n\$sql = \"UPDATE users SET name = :name WHERE id = :id\";\nDatabase::statement(\$sql, ['name' => 'John', 'id' => 1], 'app');",
            'comment' => 'system.doc.database.code.comment.statement',
            'alt'    => "database_statement(\$sql, ['name' => 'John', 'id' => 1], 'app');",
            'desc'    => 'system.doc.database.statement.desc'
        ],
        [
            'name'    => "select(string \$sql, array \$params = [], ?string \$key = null, string \$connection = 'default')",
            'code'    => "use System\\Core\\Database;\n\n\$sql = \"SELECT id, name FROM users WHERE active = :active\";\n\$users = Database::select(\$sql, ['active' => 1], null, 'app');",
            'comment' => 'system.doc.database.code.comment.select',
            'alt'    => "\$users = database_select(\$sql, ['active' => 1], null, 'app');",
            'desc'    => 'system.doc.database.select.desc'
        ],
        [
            'name'    => "selectRow(string \$sql, array \$params = [], ?string \$key = null, string \$connection = 'default')",
            'code'    => "use System\\Core\\Database;\n\n\$sql = \"SELECT id, name FROM users WHERE id = :id\";\n\$user = Database::selectRow(\$sql, ['id' => 1], null, 'auth');",
            'comment' => 'system.doc.database.code.comment.selectrow',
            'alt'    => "\$user = database_select_row(\$sql, ['id' => 1], null, 'auth');",
            'desc'    => 'system.doc.database.selectrow.desc'
        ],
        [
            'name'    => "getLastInsertedID(string \$connection = 'default')",
            'code'    => "use System\\Core\\Database;\n\n\$sql = \"INSERT INTO users (name) VALUES (?)\";\nDatabase::statement(\$sql, ['John'], 'app');\n\$id = Database::getLastInsertedID('app');",
            'comment' => 'system.doc.database.code.comment.getlastinsertedid',
            'alt'    => "\$id = database_get_last_inserted_id('app');",
            'desc'    => 'system.doc.database.getlastinsertedid.desc'
        ],
        [
            'name'    => "isInTransaction(string \$connection = 'default')",
            'code'    => "use System\\Core\\Database;\n\nif (Database::isInTransaction('app')) {\n    Database::rollbackTransaction('app');\n}",
            'comment' => 'system.doc.database.code.comment.isintransaction',
            'alt'    => "if (database_is_in_transaction('app')) {\n    database_rollback_transaction('app');\n}",
            'desc'    => 'system.doc.database.isintransaction.desc'
        ],
        [
            'name'    => "startTransaction(string \$connection = 'default')",
            'code'    => "use System\\Core\\Database;\n\nDatabase::startTransaction('app');\nDatabase::statement(\"UPDATE users SET active = ? WHERE id = ?\", [1, 10], 'app');",
            'comment' => 'system.doc.database.code.comment.starttransaction',
            'alt'    => "database_start_transaction('app');",
            'desc'    => 'system.doc.database.starttransaction.desc'
        ],
        [
            'name'    => "commitTransaction(string \$connection = 'default')",
            'code'    => "use System\\Core\\Database;\n\nDatabase::startTransaction('app');\nDatabase::statement(\"UPDATE users SET active = ? WHERE id = ?\", [1, 10], 'app');\nDatabase::commitTransaction('app');",
            'comment' => 'system.doc.database.code.comment.committransaction',
            'alt'    => "database_commit_transaction('app');",
            'desc'    => 'system.doc.database.committransaction.desc'
        ],
        [
            'name'    => "rollbackTransaction(string \$connection = 'default')",
            'code'    => "use System\\Core\\Database;\n\nDatabase::startTransaction('app');\nDatabase::statement(\"DELETE FROM users WHERE id = ?\", [10], 'app');\nDatabase::rollbackTransaction('app');",
            'comment' => 'system.doc.database.code.comment.rollbacktransaction',
            'alt'    => "database_rollback_transaction('app');",
            'desc'    => 'system.doc.database.rollbacktransaction.desc'
        ],
        [
            'name'    => 'disconnect(?string $connection = null)',
            'code'    => "use System\\Core\\Database;\n\nDatabase::disconnect('app');\nDatabase::disconnect();",
            'comment' => 'system.doc.database.code.comment.disconnect',
            'alt'    => "database_disconnect('app');\ndatabase_disconnect();",
            'desc'    => 'system.doc.database.disconnect.desc'
        ],
    ],
    'System\\Core\\Language' => [
        [
            'name'    => 'get(?string $key = null, ?array $replacements = null, ?string $lang = null)',
            'code'    => "use System\\Core\\Language;\n\n\$all = Language::get();\n\$title = Language::get(\"system.template.framework.name\");",
            'comment' => 'system.doc.language.code.comment.get.all',
            'alt'    => "\$title = lg(\"system.template.framework.name\");\n\$title = language_get(\"system.template.framework.name\");",
            'desc'    => 'system.doc.language.get.desc'
        ],
        [
            'name'    => 'getByPrefix(string $prefix, ?string $lang = null)',
            'code'    => "use System\\Core\\Language;\n\n\$translations = Language::getByPrefix('app.pages', 'en');",
            'comment' => 'system.doc.language.code.comment.getByPrefix',
            'alt'    => "\$translations = language_get_by_prefix('app.pages', 'en');",
            'desc'    => 'system.doc.language.getByPrefix.desc'
        ],
        [
            'name'    => 'normalizePrefix(string $prefix)',
            'code'    => "use System\\Core\\Language;\n\n\$prefix = Language::normalizePrefix('app.pages');",
            'comment' => 'system.doc.language.code.comment.normalizePrefix',
            'alt'    => "\$prefix = language_normalize_prefix('app.pages');",
            'desc'    => 'system.doc.language.normalizePrefix.desc'
        ],
        [
            'name'    => 'currentLang()',
            'code'    => "use System\\Core\\Language;\n\n\$lang = Language::currentLang();",
            'comment' => 'system.doc.language.code.comment.current',
            'alt'    => "\$lang = language_current();",
            'desc'    => 'system.doc.language.current.desc'
        ],
        [
            'name'    => 'load(?string $lang = null)',
            'code'    => "use System\\Core\\Language;\n\nLanguage::load(\"pt-br\");",
            'comment' => 'system.doc.language.code.comment.load',
            'alt'    => "language_load(\"pt-br\");\nld(\"pt-br\");",
            'desc'    => 'system.doc.language.load.desc'
        ],
        [
            'name'    => 'defaultLang()',
            'code'    => "use System\\Core\\Language;\n\n\$lang = Language::defaultLang();",
            'comment' => 'system.doc.language.code.comment.default',
            'alt'    => "\$lang = language_default();",
            'desc'    => 'system.doc.language.default.desc'
        ],
        [
            'name'    => 'detect()',
            'code'    => "use System\\Core\\Language;\n\n\$lang = Language::detect();",
            'comment' => 'system.doc.language.code.comment.detect',
            'alt'    => "\$lang = language_detect();",
            'desc'    => 'system.doc.language.detect.desc'
        ],
    ],
    'System\\Core\\Path' => [
        [
            'name'    => 'root()',
            'code'    => "use System\\Core\\Path;\n\n\$root = Path::root();",
            'comment' => 'system.doc.path.code.comment.root',
            'alt'    => "\$root = path_root();",
            'desc'    => 'system.doc.path.root.desc'
        ],
        [
            'name'    => 'app()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::app();",
            'comment' => 'system.doc.path.code.comment.app',
            'alt'    => "\$path = path_app();",
            'desc'    => 'system.doc.path.app.desc'
        ],
        [
            'name'    => 'appBootable()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::appBootable();",
            'comment' => 'system.doc.path.code.comment.appBootable',
            'alt'    => "\$path = path_app_bootable();",
            'desc'    => 'system.doc.path.appBootable.desc'
        ],
        [
            'name'    => 'appHelpers()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::appHelpers();",
            'comment' => 'system.doc.path.code.comment.appHelpers',
            'alt'    => "\$path = path_app_helpers();",
            'desc'    => 'system.doc.path.appHelpers.desc'
        ],
        [
            'name'    => 'appLanguages()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::appLanguages();",
            'comment' => 'system.doc.path.code.comment.appLanguages',
            'alt'    => "\$path = path_app_languages();",
            'desc'    => 'system.doc.path.appLanguages.desc'
        ],
        [
            'name'    => 'appRoutes()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::appRoutes();",
            'comment' => 'system.doc.path.code.comment.appRoutes',
            'alt'    => "\$path = path_app_routes();",
            'desc'    => 'system.doc.path.appRoutes.desc'
        ],
        [
            'name'    => 'appMiddlewares()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::appMiddlewares();",
            'comment' => 'system.doc.path.code.comment.appMiddlewares',
            'alt'    => "\$path = path_app_middlewares();",
            'desc'    => 'system.doc.path.appMiddlewares.desc'
        ],
        [
            'name'    => 'appControllers()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::appControllers();",
            'comment' => 'system.doc.path.code.comment.appControllers',
            'alt'    => "\$path = path_app_controllers();",
            'desc'    => 'system.doc.path.appControllers.desc'
        ],
        [
            'name'    => 'appModels()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::appModels();",
            'comment' => 'system.doc.path.code.comment.appModels',
            'alt'    => "\$path = path_app_models();",
            'desc'    => 'system.doc.path.appModels.desc'
        ],
        [
            'name'    => 'appViews()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::appViews();",
            'comment' => 'system.doc.path.code.comment.appViews',
            'alt'    => "\$path = path_app_views();",
            'desc'    => 'system.doc.path.appViews.desc'
        ],
        [
            'name'    => 'appViewsPages()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::appViewsPages();",
            'comment' => 'system.doc.path.code.comment.appViewsPages',
            'alt'    => "\$path = path_app_views_pages();",
            'desc'    => 'system.doc.path.appViewsPages.desc'
        ],
        [
            'name'    => 'appViewsTemplates()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::appViewsTemplates();",
            'comment' => 'system.doc.path.code.comment.appViewsTemplates',
            'alt'    => "\$path = path_app_views_templates();",
            'desc'    => 'system.doc.path.appViewsTemplates.desc'
        ],
        [
            'name'    => 'system()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::system();",
            'comment' => 'system.doc.path.code.comment.system',
            'alt'    => "\$path = path_system();",
            'desc'    => 'system.doc.path.system.desc'
        ],
        [
            'name'    => 'systemInterfaces()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::systemInterfaces();",
            'comment' => 'system.doc.path.code.comment.systemInterfaces',
            'alt'    => "\$path = path_system_interfaces();",
            'desc'    => 'system.doc.path.systemInterfaces.desc'
        ],
        [
            'name'    => 'systemHelpers()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::systemHelpers();",
            'comment' => 'system.doc.path.code.comment.systemHelpers',
            'alt'    => "\$path = path_system_helpers();",
            'desc'    => 'system.doc.path.systemHelpers.desc'
        ],
        [
            'name'    => 'systemLanguages()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::systemLanguages();",
            'comment' => 'system.doc.path.code.comment.systemLanguages',
            'alt'    => "\$path = path_system_languages();",
            'desc'    => 'system.doc.path.systemLanguages.desc'
        ],
        [
            'name'    => 'systemRoutes()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::systemRoutes();",
            'comment' => 'system.doc.path.code.comment.systemRoutes',
            'alt'    => "\$path = path_system_routes();",
            'desc'    => 'system.doc.path.systemRoutes.desc'
        ],
        [
            'name'    => 'systemMiddlewares()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::systemMiddlewares();",
            'comment' => 'system.doc.path.code.comment.systemMiddlewares',
            'alt'    => "\$path = path_system_middlewares();",
            'desc'    => 'system.doc.path.systemMiddlewares.desc'
        ],
        [
            'name'    => 'systemControllers()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::systemControllers();",
            'comment' => 'system.doc.path.code.comment.systemControllers',
            'alt'    => "\$path = path_system_controllers();",
            'desc'    => 'system.doc.path.systemControllers.desc'
        ],
        [
            'name'    => 'systemModels()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::systemModels();",
            'comment' => 'system.doc.path.code.comment.systemModels',
            'alt'    => "\$path = path_system_models();",
            'desc'    => 'system.doc.path.systemModels.desc'
        ],
        [
            'name'    => 'systemViews()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::systemViews();",
            'comment' => 'system.doc.path.code.comment.systemViews',
            'alt'    => "\$path = path_system_views();",
            'desc'    => 'system.doc.path.systemViews.desc'
        ],
        [
            'name'    => 'systemViewsPages()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::systemViewsPages();",
            'comment' => 'system.doc.path.code.comment.systemViewsPages',
            'alt'    => "\$path = path_system_views_pages();",
            'desc'    => 'system.doc.path.systemViewsPages.desc'
        ],
        [
            'name'    => 'systemViewsTemplates()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::systemViewsTemplates();",
            'comment' => 'system.doc.path.code.comment.systemViewsTemplates',
            'alt'    => "\$path = path_system_views_templates();",
            'desc'    => 'system.doc.path.systemViewsTemplates.desc'
        ],
        [
            'name'    => 'systemIncludes()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::systemIncludes();",
            'comment' => 'system.doc.path.code.comment.systemIncludes',
            'alt'    => "\$path = path_system_includes();",
            'desc'    => 'system.doc.path.systemIncludes.desc'
        ],
        [
            'name'    => 'public()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::public();",
            'comment' => 'system.doc.path.code.comment.public',
            'alt'    => "\$path = path_public();",
            'desc'    => 'system.doc.path.public.desc'
        ],
        [
            'name'    => 'storage()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::storage();",
            'comment' => 'system.doc.path.code.comment.storage',
            'alt'    => "\$path = path_storage();",
            'desc'    => 'system.doc.path.storage.desc'
        ],
        [
            'name'    => 'storageSessions()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::storageSessions();",
            'comment' => 'system.doc.path.code.comment.storageSessions',
            'alt'    => "\$path = path_storage_sessions();",
            'desc'    => 'system.doc.path.storageSessions.desc'
        ],
        [
            'name'    => 'storageLogs()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::storageLogs();",
            'comment' => 'system.doc.path.code.comment.storageLogs',
            'alt'    => "\$path = path_storage_logs();",
            'desc'    => 'system.doc.path.storageLogs.desc'
        ],
        [
            'name'    => 'languages()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::languages();",
            'comment' => 'system.doc.path.code.comment.languages',
            'alt'    => "\$path = path_languages();",
            'desc'    => 'system.doc.path.languages.desc'
        ],
        [
            'name'    => 'basePath()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::basePath();",
            'comment' => 'system.doc.path.code.comment.basePath',
            'alt'    => "\$path = path_base();",
            'desc'    => 'system.doc.path.basePath.desc'
        ],
        [
            'name'    => 'basePathPublic()',
            'code'    => "use System\\Core\\Path;\n\n\$path = Path::basePathPublic();",
            'comment' => 'system.doc.path.code.comment.basePathPublic',
            'alt'    => "\$path = path_base_public();",
            'desc'    => 'system.doc.path.basePathPublic.desc'
        ],
        [
            'name'    => 'siteURL(?string $final = null)',
            'code'    => "use System\\Core\\Path;\n\n\$url = Path::siteURL('dashboard');",
            'comment' => 'system.doc.path.code.comment.siteURL',
            'alt'    => "\$url = site_url('dashboard');",
            'desc'    => 'system.doc.path.siteURL.desc'
        ],
    ],
    'System\\Core\\Response' => [
        [
            'name'    => 'redirect(string $uri = \'\', string $method = \'auto\', ?int $code = null)',
            'code'    => "use System\\Core\\Response;\n\nreturn Response::redirect('/login');",
            'comment' => 'system.doc.response.code.comment.redirect',
            'alt'    => "return response_redirect('/login');",
            'desc'    => 'system.doc.response.redirect.desc'
        ],
        [
            'name'    => 'html(string $html, int $status = 200)',
            'code'    => "use System\\Core\\Response;\n\nreturn Response::html('&lt;h1&gt;Hello&lt;/h1&gt;');",
            'comment' => 'system.doc.response.code.comment.html',
            'alt'    => "return response_html('&lt;h1&gt;Hello&lt;/h1&gt;');",
            'desc'    => 'system.doc.response.html.desc'
        ],
        [
            'name'    => 'text(string $text, int $status = 200)',
            'code'    => "use System\\Core\\Response;\n\nreturn Response::text('Hello');",
            'comment' => 'system.doc.response.code.comment.text',
            'alt'    => "return response_text('Hello');",
            'desc'    => 'system.doc.response.text.desc'
        ],
        [
            'name'    => 'json(array|string $data, int $status = 200)',
            'code'    => "use System\\Core\\Response;\n\nreturn Response::json(['status' => 'ok']);",
            'comment' => 'system.doc.response.code.comment.json',
            'alt'    => "return response_json(['status' => 'ok']);",
            'desc'    => 'system.doc.response.json.desc'
        ],
        [
            'name'    => 'xml(string $xml, int $status = 200)',
            'code'    => "use System\\Core\\Response;\n\nreturn Response::xml('&lt;message&gt;Hello&lt;/message&gt;');",
            'comment' => 'system.doc.response.code.comment.xml',
            'alt'    => "return response_xml('&lt;message&gt;Hello&lt;/message&gt;');",
            'desc'    => 'system.doc.response.xml.desc'
        ],
        [
            'name'    => 'file(string $filePath, string $downloadName, string $hashFile, string $contentType = \'application/octet-stream\')',
            'code'    => "use System\\Core\\Response;\n\nreturn Response::file(\$filePath, \$downloadName, \$hashFile, \$contentType);",
            'comment' => 'system.doc.response.code.comment.file',
            'alt'    => "return response_file(\$filePath, \$downloadName, \$hashFile, \$contentType);",
            'desc'    => 'system.doc.response.file.desc'
        ],
    ],
    'System\\Core\\Session' => [
        [
            'name'    => 'start()',
            'code'    => "use System\\Core\\Session;\n\nSession::start();",
            'comment' => 'system.doc.session.code.comment.start',
            'alt'    => "session_start_safe();",
            'desc'    => 'system.doc.session.start.desc'
        ],
        [
            'name'    => 'has(string $key)',
            'code'    => "use System\\Core\\Session;\n\nif (Session::has('user_id')) {\n    // your logic\n}",
            'comment' => 'system.doc.session.code.comment.has',
            'alt'    => "session_has('user_id');",
            'desc'    => 'system.doc.session.has.desc'
        ],
        [
            'name'    => 'get(string $key, mixed $default = null)',
            'code'    => "use System\\Core\\Session;\n\n\$userId = Session::get('user_id', 0);",
            'comment' => 'system.doc.session.code.comment.get',
            'alt'    => "\$userId = session_get('user_id', 0);",
            'desc'    => 'system.doc.session.get.desc'
        ],
        [
            'name'    => 'set(string $key, mixed $value)',
            'code'    => "use System\\Core\\Session;\n\nSession::set('user_id', 123);",
            'comment' => 'system.doc.session.code.comment.set',
            'alt'    => "session_set('user_id', 123);",
            'desc'    => 'system.doc.session.set.desc'
        ],
        [
            'name'    => 'setMany(array $items)',
            'code'    => "use System\\Core\\Session;\n\nSession::setMany([\n  'user_id' => 123,\n  'user_role' => 'admin'\n]);",
            'comment' => 'system.doc.session.code.comment.setMany',
            'alt'    => "session_set_many([\n  'user_id' => 123,\n  'user_role' => 'admin'\n]);",
            'desc'    => 'system.doc.session.setMany.desc'
        ],
        [
            'name'    => 'forget(string $key)',
            'code'    => "use System\\Core\\Session;\n\nSession::forget('user_id');",
            'comment' => 'system.doc.session.code.comment.forget',
            'alt'    => "session_forget('user_id');",
            'desc'    => 'system.doc.session.forget.desc'
        ],
        [
            'name'    => 'clear()',
            'code'    => "use System\\Core\\Session;\n\nSession::clear();",
            'comment' => 'system.doc.session.code.comment.clear',
            'alt'    => "session_clear();",
            'desc'    => 'system.doc.session.clear.desc'
        ],
        [
            'name'    => 'save()',
            'code'    => "use System\\Core\\Session;\n\nSession::save();",
            'comment' => 'system.doc.session.code.comment.save',
            'alt'    => "session_save();",
            'desc'    => 'system.doc.session.save.desc'
        ],
        [
            'name'    => 'destroy()',
            'code'    => "use System\\Core\\Session;\n\nSession::destroy();",
            'comment' => 'system.doc.session.code.comment.destroy',
            'alt'    => "session_destroy_safe();",
            'desc'    => 'system.doc.session.destroy.desc'
        ],
        [
            'name'    => 'regenerate(bool $deleteOldSession = true)',
            'code'    => "use System\\Core\\Session;\n\nSession::regenerate();",
            'comment' => 'system.doc.session.code.comment.regenerate',
            'alt'    => "session_regenerate();",
            'desc'    => 'system.doc.session.regenerate.desc'
        ],
    ],
    'System\\Core\\View' => [
        [
            'name'    => 'share(string $key, mixed $value)',
            'code'    => "use System\\Core\\View;\n\nView::share('app_name', 'Mini ERP');",
            'comment' => 'system.doc.view.code.comment.share',
            'alt'    => "view_share('app_name', 'Mini ERP');",
            'desc'    => 'system.doc.view.share.desc'
        ],
        [
            'name'    => 'shareMany(array $items)',
            'code'    => "use System\\Core\\View;\n\nView::shareMany([\n  'user' => \$user,\n  'language' => 'pt-BR'\n]);",
            'comment' => 'system.doc.view.code.comment.shareMany',
            'alt'    => "view_share_many([\n  'user' => \$user,\n  'language' => 'pt-BR'\n]);",
            'desc'    => 'system.doc.view.shareMany.desc'
        ],
        [
            'name'    => 'forget(string $key)',
            'code'    => "use System\\Core\\View;\n\nView::forget('language');",
            'comment' => 'system.doc.view.code.comment.forget',
            'alt'    => "view_forget('language');",
            'desc'    => 'system.doc.view.forget.desc'
        ],
        [
            'name'    => 'forgetMany(array $keys)',
            'code'    => "use System\\Core\\View;\n\nView::forgetMany(['user', 'language']);",
            'comment' => 'system.doc.view.code.comment.forgetMany',
            'alt'    => "view_forget_many(['user', 'language']);",
            'desc'    => 'system.doc.view.forgetMany.desc'
        ],
        [
            'name'    => 'clear()',
            'code'    => "use System\\Core\\View;\n\nView::clear();",
            'comment' => 'system.doc.view.code.comment.clear',
            'alt'    => "view_clear();",
            'desc'    => 'system.doc.view.clear.desc'
        ],
        [
            'name'    => 'setTemplate(?string $relativePath = null)',
            'code'    => "use System\\Core\\View;\n\nView::setTemplate('layouts/main');",
            'comment' => 'system.doc.view.code.comment.setTemplate',
            'alt'    => "view_set_template('layouts/main');",
            'desc'    => 'system.doc.view.setTemplate.desc'
        ],
        [
            'name'    => 'getTemplate()',
            'code'    => "use System\\Core\\View;\n\n\$template = View::getTemplate();",
            'comment' => 'system.doc.view.code.comment.getTemplate',
            'alt'    => "\$template = view_get_template();",
            'desc'    => 'system.doc.view.getTemplate.desc'
        ],
        [
            'name'    => 'render_page(string $page, array $data = [])',
            'code'    => "use System\\Core\\View;\n\necho View::render_page('user-profile', ['user' => \$user]);",
            'comment' => 'system.doc.view.code.comment.renderPage',
            'alt'    => "echo view_render_page('user-profile', ['user' => \$user]);",
            'desc'    => 'system.doc.view.renderPage.desc'
        ],
        [
            'name'    => 'render_system_page(string $page, array $data = [])',
            'code'    => "use System\\Core\\View;\n\necho View::render_system_page('home');",
            'comment' => 'system.doc.view.code.comment.renderSystemPage',
            'alt'    => "echo view_render_system_page('home');",
            'desc'    => 'system.doc.view.renderSystemPage.desc'
        ],
        [
            'name'    => 'render_html(string $html, array $data = [])',
            'code'    => "use System\\Core\\View;\n\necho View::render_html('&lt;h1&gt;Hello&lt;/h1&gt;');",
            'comment' => 'system.doc.view.code.comment.renderHtml',
            'alt'    => "echo view_render_html('&lt;h1&gt;Hello&lt;/h1&gt;');",
            'desc'    => 'system.doc.view.renderHtml.desc'
        ],
        [
            'name'    => 'render_vue(string $page, array $data = [], ?string $entrypoint = null, array|string|null $i18nPrefixes = null, ?string $lang = null)',
            'code'    => "use System\\Core\\View;\n\n\$html = View::render_vue('account/Profile', [\n    'title' => 'Account',\n    'user' => ['name' => 'Vitor'],\n], null, ['app.pages.account']);",
            'comment' => 'system.doc.view.code.comment.renderVue',
            'alt'    => "return response_html(view_render_vue('account/Profile', [\n    'title' => 'Account',\n    'user' => ['name' => 'Vitor'],\n], null, ['app.pages.account']));\n\nreturn response_html(view_render_vue('admin/Users', ['title' => 'Users'], 'admin.js', ['app.pages.admin'], 'en'));",
            'desc'    => 'system.doc.view.renderVue.desc'
        ],
        [
            'name'    => 'getGlobals()',
            'code'    => "use System\\Core\\View;\n\n\$globals = View::getGlobals();",
            'comment' => 'system.doc.view.code.comment.getGlobals',
            'alt'    => "\$globals = view_globals();",
            'desc'    => 'system.doc.view.getGlobals.desc'
        ],
    ],
    'System\\Core\\FormValidator' => [
        [
            'name'    => 'validate(array $rules)',
            'code'    => "use System\\Core\\FormValidator;\n\n\$form = new FormValidator();\n\$form->setForm(\$_POST);\n\$isValid = \$form->validate([\n    'email' => 'required|email',\n    'password' => 'required|min:8'\n]);\n\nif (!\$isValid) {\n    \$errors = \$form->errors();\n}",
            'comment' => '',
            'alt'    => "\$form = form_validator(\$_POST);\n\nif (!\$form->validate([\n    'email' => 'required|email',\n    'password' => 'required|min:8'\n])) {\n    \$errors = \$form->errors();\n}",
            'desc'    => 'system.doc.form_validator.validate.desc'
        ],
        [
            'name'    => 'registerRule(string $name, callable $callback)',
            'code'    => "use System\\Core\\FormValidator;\n\n// Using the class directly\nFormValidator::registerRule('cpf', function(\$value) {\n    return preg_match('/^\\d{11}$/', \$value)\n        ? true\n        : lg('system.doc.form_validator.code.comment.cpf.error');\n});",
            'comment' => '',
            'alt'    => "form_validator_register_rule('cpf', function(\$value) {\n    return preg_match('/^\\d{11}$/', \$value)\n        ? true\n        : lg('system.doc.form_validator.code.comment.cpf.error');\n});",
            'desc'    => 'system.doc.form_validator.register_rule.desc'
        ],
        [
            'name'    => 'setForm(array $data)',
            'code'    => "use System\\Core\\FormValidator;\n\n\$form = new FormValidator();\n\$form->setForm(['name' => 'Vitor', 'email' => 'vitor@email.com']);",
            'comment' => '',
            'alt'    => "form_validator(['name' => 'Vitor', 'email' => 'vitor@email.com']);",
            'desc'    => 'system.doc.form_validator.setForm.desc'
        ],
        [
            'name'    => 'resetErrors()',
            'code'    => "use System\\Core\\FormValidator;\n\n\$form = new FormValidator();\n\$form->resetErrors();",
            'comment' => '',
            'alt'    => "form_validator()->resetErrors();",
            'desc'    => 'system.doc.form_validator.resetErrors.desc'
        ],
        [
            'name'    => 'errors()',
            'code'    => "use System\\Core\\FormValidator;\n\n\$form = new FormValidator();\n\$errors = \$form->errors();",
            'comment' => '',
            'alt'    => "\$errors = form_validator()->errors();",
            'desc'    => 'system.doc.form_validator.errors.desc'
        ],
        [
            'name'    => 'get(string $key, mixed $default = null)',
            'code'    => "use System\\Core\\FormValidator;\n\n\$form = new FormValidator();\n\$form->setForm(['age' => 25]);\n\$age = \$form->get('age');",
            'comment' => 'system.doc.form_validator.code.comment.get',
            'alt'    => "\$age = form_validator(['age' => 25])->get('age');",
            'desc'    => 'system.doc.form_validator.get.desc'
        ],
        [
            'name'    => 'has(string $key)',
            'code'    => "use System\\Core\\FormValidator;\n\n\$form = new FormValidator();\n\$form->setForm(['token' => 'abc']);\nif (\$form->has('token')) {\n    // your logic\n}",
            'comment' => 'system.doc.form_validator.code.comment.has',
            'alt'    => "if (form_validator(['token' => 'abc'])->has('token')) {\n    // your logic\n}",
            'desc'    => 'system.doc.form_validator.has.desc'
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
            <?= lg("system.doc.alternatively") ?>
            <pre><code><?= $method['alt'] ?></code></pre>
            <footer><em><?= lg($method['desc']) ?></em></footer>
        </details>
        <?php endforeach; ?>
    </details>
</section>
<?php endforeach; ?>

