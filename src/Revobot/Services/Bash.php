<?php

namespace Revobot\Services;

class Bash
{
    private const URL = 'http://rzhunemogu.ru/RandJSON.aspx?CType=5';

    public static function get(){
        $text = file_get_contents(self::URL);
        $text = iconv('CP1251', 'UTF-8', $text);
        $text = substr($text, 12);
        $text = substr($text, 0, -2);
        return is_null($text) ? 'Сервер не доступен' : $text;
    }

}