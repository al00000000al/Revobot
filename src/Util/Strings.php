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

    public static function cyrillicOnly(string $string): string
    {
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
    ): string {
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function xor(string $a, string $b)
    {
        $result = '';
        for ($i = 0; $i < strlen($a); $i++) {
            $result .= dechex(hexdec($a[$i]) ^ hexdec($b[$i]));
        }
        return $result;
    }

    public static function parseCommandArguments($argsString)
    {
        $pattern = '/--([\w\d\_]+)\s+([^\s]+)/';
        preg_match_all($pattern, $argsString, $matches);

        $args = [];
        if (!empty($matches[1])) {
            foreach ($matches[1] as $index => $key) {
                $args[$key] = $matches[2][$index] ?? null;
            }
        }

        return $args;
    }

    public static function cleanCommandArguments($original_string)
    {
        $pattern = '/--\S+?\s*/';
        $cleaned_string = preg_replace($pattern, '', $original_string);
        return $cleaned_string;
    }

    public static function img2base64($path)
    {
        if (!file_exists($path)) {
            return '';
        }
        $data = file_get_contents($path);
        return 'data:image/jpg;base64,' . base64_encode($data);
    }
}
