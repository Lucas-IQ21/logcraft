<?php
require_once __DIR__ . '/../model/User.php';

class UserController {
    public function index() {
        $users = User::getAll();
        include __DIR__ . '/../vue/users.php';
    }
}
