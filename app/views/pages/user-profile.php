<h1><?= lg("pages.users.profile"); ?></h1>

<p><strong><?= lg("pages.users.profile.id"); ?></strong> <?= htmlspecialchars($user['id']) ?></p>
<p><strong><?= lg("pages.users.profile.name"); ?></strong> <?= htmlspecialchars($user['name']) ?></p>
<p><strong><?= lg("pages.users.profile.email"); ?></strong> <?= htmlspecialchars($user['email']) ?></p>

<hr>
<a href="<?= path_base() ?>/"><?= lg("back.home"); ?></a>