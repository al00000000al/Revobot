<?php

namespace Revobot;

class Config {
    public static function get(string $key) {
        global $config;
        return ($config[$key] ?? $key);
    }

    public static function getArr(string $key) {
        global $config;
        return $config[$key] ?? [];
    }

    public static function getInt(string $key) {
        global $config;
        return (int)$config[$key] ?? 0;
    }
}
