<!DOCTYPE html>
<html>
<head><title>Liste des utilisateurs</title></head>
<body>
    <h1>Utilisateurs</h1>
    <ul>
        <?php foreach ($users as $user): ?>
            <li><?= htmlspecialchars($user->username) ?> </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>
