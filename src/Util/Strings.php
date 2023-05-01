<?php

namespace Revobot\Util;

class Strings
{


    /**
     * @param string $string
     * @return string[]
     */
    public static function stringToWords(string $string): array
    {
        return explode(" ", $string);
    }

    public static function cyrillicOnly(string $string) : string{
        return (string) preg_replace('/[^а-яА-ЯёЁ]/ui', '', $string);
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



    public static function xor(string $a, string $b) {
        $result = '';
        for ($i = 0; $i < strlen($a); $i++) {
            $result .= dechex(hexdec($a[$i]) ^ hexdec($b[$i]));
        }
        return $result;
      }

}
