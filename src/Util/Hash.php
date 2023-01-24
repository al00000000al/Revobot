<?php

namespace Revobot\Util;

class Hash
{
    public static function generate(...$args)
    {
        return hash('sha256', implode(':', $args) . SECRET_KEY);
    }

    public static function verify($user_hash, ...$args){
        return hash_equals(self::generate($args), $user_hash);
    }
}
