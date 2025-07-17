<?php
require_once __DIR__ . '/../config/bdd.php';

class Log {
    public $id;
    public $receivedAt;
    public $message;

    public function __construct($id, $receivedAt, $message) {
        $this->id = $id;
        $this->receivedAt = $receivedAt;
        $this->message = $message;
    }

    public static function getAll() {
        $pdo = Database::connect('syslog');
        $stmt = $pdo->query("SELECT * FROM SystemEvents");

        $logs = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $logs[] = new Log($row['ID'], $row['ReceivedAt'], $row['Message']);
        }
        return $logs;
    }

    public static function getById($id) {
        $pdo = Database::connect('syslog');
        $stmt = $pdo->prepare("SELECT * FROM SystemEvents WHERE ID = ?");
        $stmt->execute([$id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new Log($row['ID'], $row['ReceivedAt'], $row['Message']);
        }
        return null;
    }

}