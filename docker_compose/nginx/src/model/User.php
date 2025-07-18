<?php
require_once __DIR__ . '/../config/Database.php';

class User {
    public $id;
    public $username;
    public $password;
    public $connection;

    public function __construct($id, $username, $password, $connection) {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this->connection = $connection;
    }

    public static function getAll($connectionName = 'logcraft') {
        $pdo = Database::connect($connectionName);
        $stmt = $pdo->query("SELECT * FROM user");

        $users = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $users[] = new User($row['id'], $row['username'], $row['password'], $connectionName);
        }
        return $users;
    }

    public static function getByUsername($username, $connectionName = 'logcraft') {
        $pdo = Database::connect($connectionName);
        $stmt = $pdo->prepare("SELECT * FROM user WHERE username = ?");
        $stmt->execute([$username]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new User($row['id'], $row['username'], $row['password'], $connectionName);
        }
        return null;
    }


}
