<?php
require_once __DIR__ . '/controller/UserController.php';
require_once __DIR__ .'/controller/LogController.php';
session_start();

$uri = $_GET['page'] ?? 'login';

switch ($uri) {
    case 'login':
        require_once 'controller/AuthController.php';
        AuthController::login();
        break;

    case 'logs':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }
        require_once 'controller/LogController.php';
        LogController::showLogs();
        break;

    case 'logout':
        session_destroy();
        header('Location: index.php?page=login');
        exit;
    
    case 'logsParsed':
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }
        require_once 'controller/ParsedLogController.php';
        ParsedLogController::showParsedLogs();
        break;

    default:
        require 'view/404.php';
        break;
}
