<?php
require_once __DIR__ . '/../model/User.php';

class LogController {
    public function index() {
        $users = User::getAll();
        include __DIR__ . '/../vue/logs.php';
    }
}
