<?php

namespace Revobot;
require_once __DIR__.'/../config.php';
class Config {
    public static function get(string $key) {
        global $config;
        return $config[$key] ?? $key;
    }

    public static function getArr(string $key) {
        global $config;
        require_once __DIR__.'/../config.php';
        return (array)$config[$key] ?? [];
    }

    public static function getInt(string $key) {
        global $config;
        require_once __DIR__.'/../config.php';
        return (int)$config[$key] ?? 0;
    }
}
