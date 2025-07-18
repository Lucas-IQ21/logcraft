<?php
class ParsedLog {
    public $action;
    public $user;
    public $path;
    public $timestamp;

    public function __construct($action, $user, $path, $timestamp) {
        $this->action = $action;
        $this->user = $user;
        $this->path = $path;
        $this->timestamp = $timestamp;
    }

    public static function getGroupedLogs() {
        $rawLogs = Log::getAll();
        $grouped = [];

        foreach ($rawLogs as $log) {
            $parsed = self::parseMessage($log->message, $log->receivedAt);
            if ($parsed !== null) {
                $action = $parsed->action;
                $user = $parsed->user;

                if (!isset($grouped[$action])) {
                    $grouped[$action] = [];
                }
                if (!isset($grouped[$action][$user])) {
                    $grouped[$action][$user] = [];
                }

                $grouped[$action][$user][] = $parsed;
            }
        }

        return $grouped;
    }

    private static function parseMessage($message, $timestamp) {
        // Requete
        if (preg_match('/"(GET|POST|DELETE|PUT|PROPFIND|REPORT|HEAD) (.*?) HTTP\/[0-9.]+"/', $message, $matches)) {
            $action = $matches[1];
            $path = $matches[2];

            // User
            if (preg_match('#/files/([^/]+)#', $path, $userMatch)) {
                $user = urldecode($userMatch[1]);

                return new ParsedLog($action, $user, $path, $timestamp);
            }
        }
        return null;
    }
}
