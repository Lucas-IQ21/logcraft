<?php
require_once __DIR__ . '/../model/Log.php';

class LogController {
    public static function showLogs() {
        $logs = Log::getAll();
        require __DIR__ . '/../vue/logs.php';
    }
}

