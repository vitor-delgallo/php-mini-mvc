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

<section>
    <details>
        <summary><strong>System\Config\Database</strong></summary>

        <article>
            <h4>env()</h4>
            <pre><code>
use System\Config\Database;

$driver = Database::env(); // <?= lg("doc.database.code.comment.env") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
database_driver();
            </code></pre>
            <footer><em><?= lg("doc.database.env.desc") ?></em></footer>
        </article>

        <article>
            <h4>is(string $env)</h4>
            <pre><code>
use System\Config\Database;

if (Database::is('mysql')) {
    // <?= lg("doc.database.code.comment.is") ?>

}
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
database_is('mysql');
            </code></pre>
            <footer><em><?= lg("doc.database.is.desc") ?></em></footer>
        </article>

        <article>
            <h4>isMysql()</h4>
            <pre><code>
use System\Config\Database;

if (Database::isMysql()) {
    // <?= lg("doc.database.code.comment.ismysql") ?>

}
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
database_is_mysql();
            </code></pre>
            <footer><em><?= lg("doc.database.ismysql.desc") ?></em></footer>
        </article>

        <article>
            <h4>isPostgres()</h4>
            <pre><code>
use System\Config\Database;

if (Database::isPostgres()) {
    // <?= lg("doc.database.code.comment.ispostgres") ?>

}
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
database_is_postgres();
            </code></pre>
            <footer><em><?= lg("doc.database.ispostgres.desc") ?></em></footer>
        </article>

        <article>
            <h4>isNone()</h4>
            <pre><code>
use System\Config\Database;

if (Database::isNone()) {
    // <?= lg("doc.database.code.comment.isnone") ?>

}
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
database_is_none();
            </code></pre>
            <footer><em><?= lg("doc.database.isnone.desc") ?></em></footer>
        </article>

    </details>
</section>

<section>
    <details>
        <summary><strong>System\Config\Session</strong></summary>

        <article>
            <h4>env()</h4>
            <pre><code>
use System\Config\Session;

$driver = Session::env(); // <?= lg("doc.session.code.comment.env") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
session_driver();
            </code></pre>
            <footer><em><?= lg("doc.session.env.desc") ?></em></footer>
        </article>

        <article>
            <h4>is(string $env)</h4>
            <pre><code>
use System\Config\Session;

if (Session::is('db')) {
    // <?= lg("doc.session.code.comment.is") ?>

}
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
session_is('db');
            </code></pre>
            <footer><em><?= lg("doc.session.is.desc") ?></em></footer>
        </article>

        <article>
            <h4>isFiles()</h4>
            <pre><code>
use System\Config\Session;

if (Session::isFiles()) {
    // <?= lg("doc.session.code.comment.isfiles") ?>

}
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
session_is_files();
            </code></pre>
            <footer><em><?= lg("doc.session.isfiles.desc") ?></em></footer>
        </article>

        <article>
            <h4>isDB()</h4>
            <pre><code>
use System\Config\Session;

if (Session::isDB()) {
    // <?= lg("doc.session.code.comment.isdb") ?>

}
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
session_is_db();
            </code></pre>
            <footer><em><?= lg("doc.session.isdb.desc") ?></em></footer>
        </article>

        <article>
            <h4>isNone()</h4>
            <pre><code>
use System\Config\Session;

if (Session::isNone()) {
    // <?= lg("doc.session.code.comment.isnone") ?>

}
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
session_is_none();
            </code></pre>
            <footer><em><?= lg("doc.session.isnone.desc") ?></em></footer>
        </article>

    </details>
</section>
<section>
    <details>
        <summary><strong>System\Config\Environment</strong></summary>

        <article>
            <h4>env()</h4>
            <pre><code>
use System\Config\Environment as ConfigEnvironment;

$current = ConfigEnvironment::env(); // <?= lg("doc.environment.code.comment.env") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
environment_type();
            </code></pre>
            <footer><em><?= lg("doc.environment.env.desc") ?></em></footer>
        </article>

        <article>
            <h4>is(string $env)</h4>
            <pre><code>
use System\Config\Environment as ConfigEnvironment;

if (ConfigEnvironment::is('development')) {
    // <?= lg("doc.environment.code.comment.is") ?>

}
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
environment_is('development');
            </code></pre>
            <footer><em><?= lg("doc.environment.is.desc") ?></em></footer>
        </article>

        <article>
            <h4>isProduction()</h4>
            <pre><code>
use System\Config\Environment as ConfigEnvironment;

if (ConfigEnvironment::isProduction()) {
    // <?= lg("doc.environment.code.comment.isproduction") ?>

}
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
environment_is_production();
            </code></pre>
            <footer><em><?= lg("doc.environment.isproduction.desc") ?></em></footer>
        </article>

        <article>
            <h4>isDevelopment()</h4>
            <pre><code>
use System\Config\Environment as ConfigEnvironment;

if (ConfigEnvironment::isDevelopment()) {
    // <?= lg("doc.environment.code.comment.isdevelopment") ?>

}
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
environment_is_development();
            </code></pre>
            <footer><em><?= lg("doc.environment.isdevelopment.desc") ?></em></footer>
        </article>

        <article>
            <h4>isTesting()</h4>
            <pre><code>
use System\Config\Environment as ConfigEnvironment;

if (ConfigEnvironment::isTesting()) {
    // <?= lg("doc.environment.code.comment.istesting") ?>

}
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
environment_is_testing();
            </code></pre>
            <footer><em><?= lg("doc.environment.istesting.desc") ?></em></footer>
        </article>

    </details>
</section>
<section>
    <details>
        <summary><strong>System\Config\Globals</strong></summary>

        <article>
            <h4>get(?string $key = null)</h4>
            <pre><code>
use System\Config\Globals;

$timezone = Globals::get('CACHE_PREFIX'); // <?= lg("doc.globals.code.comment.get") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
globals_get('CACHE_PREFIX');
            </code></pre>
            <footer><em><?= lg("doc.globals.get.desc") ?></em></footer>
        </article>

        <article>
            <h4>add(string $key, mixed $value)</h4>
            <pre><code>
use System\Config\Globals;

Globals::add('CACHE_ENABLED', true); // <?= lg("doc.globals.code.comment.add") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
globals_add('CACHE_ENABLED', true);
            </code></pre>
            <footer><em><?= lg("doc.globals.add.desc") ?></em></footer>
        </article>

        <article>
            <h4>merge(array $config)</h4>
            <pre><code>
use System\Config\Globals;

Globals::merge([
    'CACHE_ENABLED' => true,
    'APP_MODE' => 'fast',
]);
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
globals_merge([
    'CACHE_ENABLED' => true,
    'APP_MODE' => 'fast',
]);
            </code></pre>
            <footer><em><?= lg("doc.globals.merge.desc") ?></em></footer>
        </article>

        <article>
            <h4>forget(string $key)</h4>
            <pre><code>
use System\Config\Globals;

Globals::forget('APP_MODE'); // <?= lg("doc.globals.code.comment.forget") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
globals_forget('APP_MODE');
            </code></pre>
            <footer><em><?= lg("doc.globals.forget.desc") ?></em></footer>
        </article>

        <article>
            <h4>forgetMany(array $keys)</h4>
            <pre><code>
use System\Config\Globals;

Globals::forgetMany(['APP_MODE', 'CACHE_ENABLED']);
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
globals_forget_many(['APP_MODE', 'CACHE_ENABLED']);
            </code></pre>
            <footer><em><?= lg("doc.globals.forgetmany.desc") ?></em></footer>
        </article>

        <article>
            <h4>reset()</h4>
            <pre><code>
use System\Config\Globals;

Globals::reset(); // <?= lg("doc.globals.code.comment.reset") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
globals_reset();
            </code></pre>
            <footer><em><?= lg("doc.globals.reset.desc") ?></em></footer>
        </article>

        <article>
            <h4>loadEnv()</h4>
            <pre><code>
use System\Config\Globals;

Globals::loadEnv(); // <?= lg("doc.globals.code.comment.loadenv") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
globals_load_env();
            </code></pre>
            <footer><em><?= lg("doc.globals.loadenv.desc") ?></em></footer>
        </article>

        <article>
            <h4>env(?string $key = null)</h4>
            <pre><code>
use System\Config\Globals;

$dbUser = Globals::env('DB_USER'); // <?= lg("doc.globals.code.comment.env") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
globals_env('DB_USER');
            </code></pre>
            <footer><em><?= lg("doc.globals.env.desc") ?></em></footer>
        </article>

    </details>
</section>
<section>
    <details>
        <summary><strong>System\Core\Autoload</strong></summary>

        <article>
            <h4>from(string $directory)</h4>
            <pre><code>
use System\Core\Autoload;

Autoload::from('/path/to/php/files'); // <?= lg("doc.autoload.code.comment.from") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
autoload_from('/path/to/php/files');
            </code></pre>
            <footer><em><?= lg("doc.autoload.from.desc") ?></em></footer>
        </article>

    </details>
</section>
<section>
    <details>
        <summary><strong>System\Core\Database</strong></summary>

        <article>
            <h4>connect()</h4>
            <pre><code>
use System\Core\Database;

$pdo = Database::connect(); // <?= lg("doc.database.code.comment.connect") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
$pdo = database_connect();
            </code></pre>
            <footer><em><?= lg("doc.database.connect.desc") ?></em></footer>
        </article>

        <article>
            <h4>statement(string $sql, array $params = [])</h4>
            <pre><code>
use System\Core\Database;

$sql = "UPDATE users SET name = ? WHERE id = ?";
Database::statement($sql, ['John', 1]); // <?= lg("doc.database.code.comment.statement") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
database_statement($sql, ['John', 1]);
            </code></pre>
            <footer><em><?= lg("doc.database.statement.desc") ?></em></footer>
        </article>

        <article>
            <h4>select(string $sql, array $params = [], ?string $key = null)</h4>
            <pre><code>
use System\Core\Database;

$sql = "SELECT * FROM users";
$users = Database::select($sql); // <?= lg("doc.database.code.comment.select") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
$users = database_select($sql);
            </code></pre>
            <footer><em><?= lg("doc.database.select.desc") ?></em></footer>
        </article>

        <article>
            <h4>selectRow(string $sql, array $params = [], ?string $key = null)</h4>
            <pre><code>
use System\Core\Database;

$sql = "SELECT * FROM users WHERE id = ?";
$user = Database::selectRow($sql, [1]); // <?= lg("doc.database.code.comment.selectrow") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
$user = database_select_row($sql, [1]);
            </code></pre>
            <footer><em><?= lg("doc.database.selectrow.desc") ?></em></footer>
        </article>

        <article>
            <h4>disconnect()</h4>
            <pre><code>
use System\Core\Database;

Database::disconnect(); // <?= lg("doc.database.code.comment.disconnect") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
database_disconnect();
            </code></pre>
            <footer><em><?= lg("doc.database.disconnect.desc") ?></em></footer>
        </article>

    </details>
</section>
<section>
    <details>
        <summary><strong>System\Core\Language</strong></summary>

        <article>
            <h4>get(?string $key = null, ?string $lang = null)</h4>
            <pre><code>
use System\Core\Language;

$all = Language::get(); // <?= lg("doc.language.code.comment.get.all") ?>
$title = Language::get("template.framework.name"); // <?= lg("doc.language.code.comment.get.key") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
$title = lg("template.framework.name");
$title = language_get("template.framework.name");
            </code></pre>
            <footer><em><?= lg("doc.language.get.desc") ?></em></footer>
        </article>

        <article>
            <h4>currentLang()</h4>
            <pre><code>
use System\Core\Language;

$lang = Language::currentLang(); // <?= lg("doc.language.code.comment.current") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
$lang = language_current();
            </code></pre>
            <footer><em><?= lg("doc.language.current.desc") ?></em></footer>
        </article>

        <article>
            <h4>load(?string $lang = null)</h4>
            <pre><code>
use System\Core\Language;

Language::load("pt-br"); // <?= lg("doc.language.code.comment.load") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
language_load("pt-br");
ld("pt-br");
            </code></pre>
            <footer><em><?= lg("doc.language.load.desc") ?></em></footer>
        </article>

        <article>
            <h4>defaultLang()</h4>
            <pre><code>
use System\Core\Language;

$lang = Language::defaultLang(); // <?= lg("doc.language.code.comment.default") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
$lang = language_default();
            </code></pre>
            <footer><em><?= lg("doc.language.default.desc") ?></em></footer>
        </article>

        <article>
            <h4>detect()</h4>
            <pre><code>
use System\Core\Language;

$lang = Language::detect(); // <?= lg("doc.language.code.comment.detect") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
$lang = language_detect();
            </code></pre>
            <footer><em><?= lg("doc.language.detect.desc") ?></em></footer>
        </article>

    </details>
</section>
<section>
    <details>
        <summary><strong>System\Core\Path</strong></summary>

        <article>
            <h4>root()</h4>
            <pre><code>
use System\Core\Path;

$root = Path::root(); // <?= lg("doc.path.code.comment.root") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
$root = path_root();
            </code></pre>
            <footer><em><?= lg("doc.path.root.desc") ?></em></footer>
        </article>

        <article>
            <h4>app()</h4>
            <pre><code>
use System\Core\Path;

$appPath = Path::app(); // <?= lg("doc.path.code.comment.app") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
$appPath = path_app();
            </code></pre>
            <footer><em><?= lg("doc.path.app.desc") ?></em></footer>
        </article>

        <article>
            <h4>appHelpers()</h4>
            <pre><code>
use System\Core\Path;

$helpers = Path::appHelpers(); // <?= lg("doc.path.code.comment.appHelpers") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
$helpers = path_app_helpers();
            </code></pre>
            <footer><em><?= lg("doc.path.appHelpers.desc") ?></em></footer>
        </article>

        <article>
            <h4>appRoutes()</h4>
            <pre><code>
use System\Core\Path;

$helpers = Path::appRoutes(); // <?= lg("doc.path.code.comment.appRoutes") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
$helpers = path_app_routes();
            </code></pre>
            <footer><em><?= lg("doc.path.appRoutes.desc") ?></em></footer>
        </article>

        <article>
            <h4>appControllers()</h4>
            <pre><code>
use System\Core\Path;

$controllers = Path::appControllers(); // <?= lg("doc.path.code.comment.appControllers") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
$controllers = path_app_controllers();
            </code></pre>
            <footer><em><?= lg("doc.path.appControllers.desc") ?></em></footer>
        </article>

        <article>
            <h4>appModels()</h4>
            <pre><code>
use System\Core\Path;

$models = Path::appModels(); // <?= lg("doc.path.code.comment.appModels") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
$models = path_app_models();
            </code></pre>
            <footer><em><?= lg("doc.path.appModels.desc") ?></em></footer>
        </article>

        <article>
            <h4>appViews()</h4>
            <pre><code>
use System\Core\Path;

$views = Path::appViews(); // <?= lg("doc.path.code.comment.appViews") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
$views = path_app_views();
            </code></pre>
            <footer><em><?= lg("doc.path.appViews.desc") ?></em></footer>
        </article>

        <article>
            <h4>appViewsPages()</h4>
            <pre><code>
use System\Core\Path;

$pages = Path::appViewsPages(); // <?= lg("doc.path.code.comment.appViewsPages") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
$pages = path_app_views_pages();
            </code></pre>
            <footer><em><?= lg("doc.path.appViewsPages.desc") ?></em></footer>
        </article>

        <article>
            <h4>appViewsTemplates()</h4>
            <pre><code>
use System\Core\Path;

$templates = Path::appViewsTemplates(); // <?= lg("doc.path.code.comment.appViewsTemplates") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
$templates = path_app_views_templates();
            </code></pre>
            <footer><em><?= lg("doc.path.appViewsTemplates.desc") ?></em></footer>
        </article>

        <article>
            <h4>system()</h4>
            <pre><code>
use System\Core\Path;

$system = Path::system(); // <?= lg("doc.path.code.comment.system") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
$system = path_system();
            </code></pre>
            <footer><em><?= lg("doc.path.system.desc") ?></em></footer>
        </article>

        <article>
            <h4>systemHelpers()</h4>
            <pre><code>
use System\Core\Path;

$helpers = Path::systemHelpers(); // <?= lg("doc.path.code.comment.systemHelpers") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
$helpers = path_system_helpers();
            </code></pre>
            <footer><em><?= lg("doc.path.systemHelpers.desc") ?></em></footer>
        </article>

        <article>
            <h4>systemIncludes()</h4>
            <pre><code>
use System\Core\Path;

$includes = Path::systemIncludes(); // <?= lg("doc.path.code.comment.systemIncludes") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
$includes = path_system_includes();
            </code></pre>
            <footer><em><?= lg("doc.path.systemIncludes.desc") ?></em></footer>
        </article>

        <article>
            <h4>public()</h4>
            <pre><code>
use System\Core\Path;

$public = Path::public(); // <?= lg("doc.path.code.comment.public") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
$public = path_public();
            </code></pre>
            <footer><em><?= lg("doc.path.public.desc") ?></em></footer>
        </article>

        <article>
            <h4>storage()</h4>
            <pre><code>
use System\Core\Path;

$storage = Path::storage(); // <?= lg("doc.path.code.comment.storage") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
$storage = path_storage();
            </code></pre>
            <footer><em><?= lg("doc.path.storage.desc") ?></em></footer>
        </article>

        <article>
            <h4>storageSessions()</h4>
            <pre><code>
use System\Core\Path;

$sessions = Path::storageSessions(); // <?= lg("doc.path.code.comment.storageSessions") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
$sessions = path_storage_sessions();
            </code></pre>
            <footer><em><?= lg("doc.path.storageSessions.desc") ?></em></footer>
        </article>

        <article>
            <h4>storageLogs()</h4>
            <pre><code>
use System\Core\Path;

$logs = Path::storageLogs(); // <?= lg("doc.path.code.comment.storageLogs") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
$logs = path_storage_logs();
            </code></pre>
            <footer><em><?= lg("doc.path.storageLogs.desc") ?></em></footer>
        </article>

        <article>
            <h4>languages()</h4>
            <pre><code>
use System\Core\Path;

$langs = Path::languages(); // <?= lg("doc.path.code.comment.languages") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
$langs = path_languages();
            </code></pre>
            <footer><em><?= lg("doc.path.languages.desc") ?></em></footer>
        </article>

        <article>
            <h4>basePath()</h4>
            <pre><code>
use System\Core\Path;

$base = Path::basePath(); // <?= lg("doc.path.code.comment.basePath") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
$base = path_base();
            </code></pre>
            <footer><em><?= lg("doc.path.basePath.desc") ?></em></footer>
        </article>

        <article>
            <h4>siteURL(?string $final = null)</h4>
            <pre><code>
use System\Core\Path;

$url = Path::siteURL("dashboard"); // <?= lg("doc.path.code.comment.siteURL") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
$url = site_url("dashboard");
            </code></pre>
            <footer><em><?= lg("doc.path.siteURL.desc") ?></em></footer>
        </article>
    </details>
</section>
<section>
    <details>
        <summary><strong>System\Core\Response</strong></summary>

        <article>
            <h4>redirect(string $uri = '', string $method = 'auto', ?int $code = null)</h4>
            <pre><code>
use System\Core\Response;

return Response::redirect("/login"); // <?= lg("doc.response.code.comment.redirect") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
return response_redirect("/login");
            </code></pre>
            <footer><em><?= lg("doc.response.redirect.desc") ?></em></footer>
        </article>

        <article>
            <h4>html(string $html, int $status = 200)</h4>
            <pre><code>
use System\Core\Response;

return Response::html("&lt;h1&gt;Hello&lt;/h1&gt;"); // <?= lg("doc.response.code.comment.html") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
return response_html("&lt;h1&gt;Hello&lt;/h1&gt;");
            </code></pre>
            <footer><em><?= lg("doc.response.html.desc") ?></em></footer>
        </article>

        <article>
            <h4>json(array|string $data, int $status = 200)</h4>
            <pre><code>
use System\Core\Response;

return Response::json(["status" => "ok"]); // <?= lg("doc.response.code.comment.json") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
return response_json(["status" => "ok"]);
            </code></pre>
            <footer><em><?= lg("doc.response.json.desc") ?></em></footer>
        </article>

        <article>
            <h4>xml(string $xml, int $status = 200)</h4>
            <pre><code>
use System\Core\Response;

return Response::xml("&lt;message&gt;Hello&lt;/message&gt;"); // <?= lg("doc.response.code.comment.xml") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
return response_xml("&lt;message&gt;Hello&lt;/message&gt;");
            </code></pre>
            <footer><em><?= lg("doc.response.xml.desc") ?></em></footer>
        </article>

    </details>
</section>
<section>
    <details>
        <summary><strong>System\Core\Session</strong></summary>

        <article>
            <h4>start()</h4>
            <pre><code>
use System\Core\Session;

Session::start(); // <?= lg("doc.session.code.comment.start") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
session_start_safe();
            </code></pre>
            <footer><em><?= lg("doc.session.start.desc") ?></em></footer>
        </article>

        <article>
            <h4>has(string $key)</h4>
            <pre><code>
use System\Core\Session;

if (Session::has("user_id")) {
    // <?= lg("doc.session.code.comment.has") ?>
}
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
if (session_has("user_id")) {
    // ...
}
            </code></pre>
            <footer><em><?= lg("doc.session.has.desc") ?></em></footer>
        </article>

        <article>
            <h4>get(string $key, mixed $default = null)</h4>
            <pre><code>
use System\Core\Session;

$userId = Session::get("user_id", 0); // <?= lg("doc.session.code.comment.get") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
$userId = session_get("user_id", 0);
            </code></pre>
            <footer><em><?= lg("doc.session.get.desc") ?></em></footer>
        </article>

        <article>
            <h4>set(string $key, mixed $value)</h4>
            <pre><code>
use System\Core\Session;

Session::set("user_id", 123); // <?= lg("doc.session.code.comment.set") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
session_set("user_id", 123);
            </code></pre>
            <footer><em><?= lg("doc.session.set.desc") ?></em></footer>
        </article>

        <article>
            <h4>setMany(array $items)</h4>
            <pre><code>
use System\Core\Session;

Session::setMany([
  "user_id" => 123,
  "user_role" => "admin"
]); // <?= lg("doc.session.code.comment.setMany") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
session_set_many([
  "user_id" => 123,
  "user_role" => "admin"
]);
            </code></pre>
            <footer><em><?= lg("doc.session.setMany.desc") ?></em></footer>
        </article>

        <article>
            <h4>forget(string $key)</h4>
            <pre><code>
use System\Core\Session;

Session::forget("user_id"); // <?= lg("doc.session.code.comment.forget") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
session_forget("user_id");
            </code></pre>
            <footer><em><?= lg("doc.session.forget.desc") ?></em></footer>
        </article>

        <article>
            <h4>clear()</h4>
            <pre><code>
use System\Core\Session;

Session::clear(); // <?= lg("doc.session.code.comment.clear") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
session_clear();
            </code></pre>
            <footer><em><?= lg("doc.session.clear.desc") ?></em></footer>
        </article>

        <article>
            <h4>save()</h4>
            <pre><code>
use System\Core\Session;

Session::save(); // <?= lg("doc.session.code.comment.save") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
session_save();
            </code></pre>
            <footer><em><?= lg("doc.session.save.desc") ?></em></footer>
        </article>

        <article>
            <h4>destroy()</h4>
            <pre><code>
use System\Core\Session;

Session::destroy(); // <?= lg("doc.session.code.comment.destroy") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
session_destroy_safe();
            </code></pre>
            <footer><em><?= lg("doc.session.destroy.desc") ?></em></footer>
        </article>

        <article>
            <h4>regenerate(bool $deleteOldSession = true)</h4>
            <pre><code>
use System\Core\Session;

Session::regenerate(); // <?= lg("doc.session.code.comment.regenerate") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
session_regenerate();
            </code></pre>
            <footer><em><?= lg("doc.session.regenerate.desc") ?></em></footer>
        </article>

    </details>
</section>
<section>
    <details>
        <summary><strong>System\Core\View</strong></summary>

        <article>
            <h4>share(string $key, mixed $value)</h4>
            <pre><code>
use System\Core\View;

View::share("app_name", "Mini ERP"); // <?= lg("doc.view.code.comment.share") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
view_share("app_name", "Mini ERP");
            </code></pre>
            <footer><em><?= lg("doc.view.share.desc") ?></em></footer>
        </article>

        <article>
            <h4>shareMany(array $items)</h4>
            <pre><code>
use System\Core\View;

View::shareMany([
  "user" => $user,
  "language" => "pt-BR"
]); // <?= lg("doc.view.code.comment.shareMany") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
view_share_many([
  "user" => $user,
  "language" => "pt-BR"
]);
            </code></pre>
            <footer><em><?= lg("doc.view.shareMany.desc") ?></em></footer>
        </article>

        <article>
            <h4>forget(string $key)</h4>
            <pre><code>
use System\Core\View;

View::forget("language"); // <?= lg("doc.view.code.comment.forget") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
view_forget("language");
            </code></pre>
            <footer><em><?= lg("doc.view.forget.desc") ?></em></footer>
        </article>

        <article>
            <h4>forgetMany(array $keys)</h4>
            <pre><code>
use System\Core\View;

View::forgetMany(["user", "language"]); // <?= lg("doc.view.code.comment.forgetMany") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
view_forget_many(["user", "language"]);
            </code></pre>
            <footer><em><?= lg("doc.view.forgetMany.desc") ?></em></footer>
        </article>

        <article>
            <h4>clear()</h4>
            <pre><code>
use System\Core\View;

View::clear(); // <?= lg("doc.view.code.comment.clear") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
view_clear();
            </code></pre>
            <footer><em><?= lg("doc.view.clear.desc") ?></em></footer>
        </article>

        <article>
            <h4>setTemplate(?string $relativePath = null)</h4>
            <pre><code>
use System\Core\View;

View::setTemplate("layouts/main"); // <?= lg("doc.view.code.comment.setTemplate") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
view_set_template("layouts/main");
            </code></pre>
            <footer><em><?= lg("doc.view.setTemplate.desc") ?></em></footer>
        </article>

        <article>
            <h4>getTemplate()</h4>
            <pre><code>
use System\Core\View;

$template = View::getTemplate(); // <?= lg("doc.view.code.comment.getTemplate") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
$template = view_get_template();
            </code></pre>
            <footer><em><?= lg("doc.view.getTemplate.desc") ?></em></footer>
        </article>

        <article>
            <h4>render_page(string $page, array $data = [])</h4>
            <pre><code>
use System\Core\View;

echo View::render_page("home", ["user" => $user]); // <?= lg("doc.view.code.comment.renderPage") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
echo view_render_page("home", ["user" => $user]);
            </code></pre>
            <footer><em><?= lg("doc.view.renderPage.desc") ?></em></footer>
        </article>

        <article>
            <h4>render_html(string $html, array $data = [])</h4>
            <pre><code>
use System\Core\View;

echo View::render_html("&lt;h1&gt;Olá&lt;/h1&gt;"); // <?= lg("doc.view.code.comment.renderHtml") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
echo view_render_html("&lt;h1&gt;Olá&lt;/h1&gt;");
            </code></pre>
            <footer><em><?= lg("doc.view.renderHtml.desc") ?></em></footer>
        </article>

        <article>
            <h4>getGlobals()</h4>
            <pre><code>
use System\Core\View;

$globals = View::getGlobals(); // <?= lg("doc.view.code.comment.getGlobals") ?>
            </code></pre>
            <?= lg("doc.alternatively") ?>
            <pre><code>
$globals = view_globals();
            </code></pre>
            <footer><em><?= lg("doc.view.getGlobals.desc") ?></em></footer>
        </article>

    </details>
</section>