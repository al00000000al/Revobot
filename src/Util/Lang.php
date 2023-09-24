<?php

namespace Revobot\Util;

class Lang
{
    public static function get(string $key): string {
        global $lang;

        if(!isset($lang[$key])){
            return $key;
        }

        return $lang[$key];
    }

}
