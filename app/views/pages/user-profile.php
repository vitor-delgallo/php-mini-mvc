<?php
use System\Core\Language;
use System\Core\Path;
?>

<h1><?= Language::get("app.pages.users.profile"); ?></h1>

<p><strong><?= Language::get("app.pages.users.profile.id"); ?></strong> <?= htmlspecialchars($user['id']) ?></p>
<p><strong><?= Language::get("app.pages.users.profile.name"); ?></strong> <?= htmlspecialchars($user['name']) ?></p>
<p><strong><?= Language::get("app.pages.users.profile.email"); ?></strong> <?= htmlspecialchars($user['email']) ?></p>

<hr>
<a href="<?= htmlspecialchars(Path::basePath() . '/') ?>"><?= Language::get("app.back.home"); ?></a>
