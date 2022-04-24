<?php

namespace Revobot\Util;

class Strings
{


    /**
     * @param $string
     * @return string[]
     */
    public static function stringToWords($string): array
    {
        return explode(" ", $string);
    }

    /**
     * @param $string
     * @return string|string[]|null
     */
    public static function cyrillicOnly($string){
        return preg_replace('/[^а-яА-ЯёЁ]/ui', '', $string);
    }

}