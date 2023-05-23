<?php

namespace Revobot\Util;

use Revobot\Config;

class Hash
{
    public static function generate(...$args)
    {
        return hash('sha256', implode(':', $args) . Config::get('secret_key'));
    }

    public static function verify($user_hash, ...$args){
        return hash_equals(self::generate($args), $user_hash);
    }
}
