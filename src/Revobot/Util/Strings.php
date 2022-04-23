<?php

namespace Revobot\Util;

class Strings
{

    public static function stringToWords($string): array
    {
        $words = array();
        $tok = strtok($string, " \t\n");
        while ($tok) {
            $words[] = $tok;
            $tok = strtok(" \t\n");
        }
        return $words;
    }

    public static function cyrillicOnly($string){
        return preg_replace('/[^а-яА-ЯёЁ]/ui', '', $string);
    }
}