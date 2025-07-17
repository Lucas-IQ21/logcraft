<?php
require_once 'config.php';

class Database {
    public static function connect($db = 'logcraft') {
        try {
            switch ($db) {
                case 'logcraft':
                    $host = DB_LOGCRAFT_HOST;
                    $dbname = DB_LOGCRAFT_NAME;
                    $user = DB_LOGCRAFT_USER;
                    $pass = DB_LOGCRAFT_PASS;
                    break;

                case 'syslog':
                    $host = DB_SYSLOG_HOST;
                    $dbname = DB_SYSLOG_NAME;
                    $user = DB_SYSLOG_USER;
                    $pass = DB_SYSLOG_PASS;
                    break;

                default:
                    throw new Exception("Configuration de base de données inconnue : $db");
            }

            $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;

        } catch (PDOException $e) {
            die("Connexion échouée à $db : " . $e->getMessage());
        }
    }
}
