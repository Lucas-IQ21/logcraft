<?php
require_once __DIR__ . '/../config/bdd.php';

class User {
    public $id;
    public $username;
    public $password;
    public $connection;

    public function __construct($id, $username, $password, $connection) {
        $this->id = $id;
        $this->username = $username;
        $this->password = $password;
        $this-> connection = $connection;
    }

    public static function getAll() {
        $pdo = Database::connect('logcraft');
        $stmt = $pdo->query("SELECT * FROM user");

        $users = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $users[] = new User($row['id'], $row['username'], $row['password'], $pdo);
        }
        return $users;
    }


    public static function getById($id) {
        $pdo = Database::connect();
        $stmt = $pdo->prepare("SELECT * FROM user WHERE id = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new User($row['id'], $row['username'], $row['password']);
        }
        return null;
    }
}
