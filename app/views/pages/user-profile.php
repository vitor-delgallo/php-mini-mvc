<h1>User Profile</h1>

<p><strong>ID:</strong> <?= htmlspecialchars($user['id']) ?></p>
<p><strong>Name:</strong> <?= htmlspecialchars($user['name']) ?></p>
<p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>

<hr>
<a href="<?= path_base() ?>/">← Back to Home</a>