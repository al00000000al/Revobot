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

    /**
     * @param int $length
     * @param string $characters
     * @return string
     */
    public static function random(
        int $length = 10,
        string $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
    ): string
    {
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

}