<?php
require_once __DIR__ . '/controller/UserController.php';
require_once __DIR__ .'/controller/LogController.php';


$route = $_GET['route'] ?? 'home';

switch ($route) {
    case 'user':
        $controller = new UserController();
        $controller->index();
        break;
    case 'log': 
        $controller =  new LogController();
        $controller->index();
        break;
    case 'home':
    default:
        echo "<h1>Bienvenue sur l'accueil</h1>";
        echo '<a href="?route=user">Voir les utilisateurs</a>';
        echo '<a href="?route=log">Voir les logs</a>';
        break;
}
