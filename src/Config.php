<?php

namespace Revobot;

class Config {
    public static function get(string $key, bool $is_arr = false) {
        global $config;
        return ($config[$key] ?? ($is_arr ? [] : $key));
    }
}
