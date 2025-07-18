<?php
require_once __DIR__ . '/../model/ParsedLog.php';
require_once __DIR__ . '/../model/Log.php';

class ParsedLogController {
    public static function showParsedLogs() {
        $groupedLogs = ParsedLog::getGroupedLogs();
        require __DIR__ . '/../vue/parsedLogs.php';
    }
}


