<?php
require_once __DIR__ . '/../model/User.php';

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    $user = User::getByUsername($username);

    if ($user && password_verify($password, $user->password)) {
        $_SESSION['user_id'] = $user->id;
        $_SESSION['username'] = $user->username;
        header('Location: logs.php');
        exit;
    } else {
        $error = "Identifiants incorrects.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">
    <form method="POST" action="index.php?page=login" class="bg-white p-8 rounded-lg shadow-md w-full max-w-sm">
        <h2 class="text-2xl font-bold mb-6 text-center">Connexion</h2>
        <?php if ($error): ?>
            <div class="bg-red-100 text-red-600 p-2 rounded mb-4"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>
        <label class="block mb-2 text-sm font-medium">Nom d'utilisateur</label>
        <input type="text" name="username" required class="w-full p-2 border rounded mb-4">

        <label class="block mb-2 text-sm font-medium">Mot de passe</label>
        <input type="password" name="password" required class="w-full p-2 border rounded mb-6">

        <button type="submit" class="w-full bg-blue-500 text-white py-2 rounded hover:bg-blue-600">Se connecter</button>
    </form>
</body>
</html>
