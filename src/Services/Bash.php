<?php

namespace Revobot\Services;

use Revobot\Util\Curl;

class Bash
{
    private const URL = 'http://rzhunemogu.ru/RandJSON.aspx?CType=';
    private const TYPES = [1, 2, 3, 4, 5, 6, 8, 11, 12, 13, 14, 15, 16, 18];

    /**
     * @return string
     */
    public static function get(): string
    {
        for ($tries = 0; $tries < 3; $tries++) {
            $content = self::getJson(self::fetch());
            if (!empty($content)) {
                return $content;
            }
        }

        return 'Сервер не доступен';
    }

    /**
     * @return string
     */
    public static function fetch(): string
    {
        return  (string) Curl::get(self::URL . self::TYPES[mt_rand(0, count(self::TYPES) - 1)]);
    }

    /**
     * @param string $text
     * @return string
     */
    public static function getJson(string $text): string
    {
        $text = iconv('cp1251', 'UTF-8', $text);
        $text = str_replace("\n", "\\n", $text);
        $text = str_replace("\r", "\\r", $text);
        $text_arr = json_decode($text, true);

        if ($text_arr && isset($text_arr['content'])) {
            return (string)$text_arr['content'];
        }
        return '';
    }
}
