<h1><?= lg("user.profile"); ?></h1>

<p><strong><?= lg("user.profile.id"); ?></strong> <?= htmlspecialchars($user['id']) ?></p>
<p><strong><?= lg("user.profile.name"); ?></strong> <?= htmlspecialchars($user['name']) ?></p>
<p><strong><?= lg("user.profile.email"); ?></strong> <?= htmlspecialchars($user['email']) ?></p>

<hr>
<a href="<?= path_base() ?>/"><?= lg("back.home"); ?></a>