<?php
require_once __DIR__ . '/../model/User.php';

class AuthController {

    public static function login() {
        $error = "";

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            $user = User::getByUsername($username);

        if ($user && $password === $user->password) {
                $_SESSION['user_id'] = $user->id;
                $_SESSION['username'] = $user->username;
                header('Location: index.php?page=logs');
                exit;
            } else {
                $error = "Identifiants incorrects.";
            }
        }

        require __DIR__ . '/../vue/login.php';
    }
}
