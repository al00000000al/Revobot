<?php

namespace Revobot\Numerology;

class Words
{


    public const LETTERS_TO_NUM = [
        'а' => 1, 'б' => 5, 'в' => 6,
        'г' => 7, 'д' => 4, 'е' => 5,
        'ё' => 6, 'ж' => 8, 'з' => 5,
        'и' => 7, 'й' => 2, 'к' => 9,
        'л' => 6, 'м' => 3, 'н' => 4,
        'о' => 4, 'п' => 3, 'р' => 3,
        'с' => 6, 'т' => 2, 'у' => 9,
        'ф' => 1, 'х' => 4, 'ц' => 3,
        'ч' => 2, 'ш' => 9, 'щ' => 1,
        'ъ' => 1, 'ы' => 5, 'ь' => 2,
        'э' => 6, 'ю' => 1, 'я' => 7,
    ];

    public const NUMBERS_RATE = [
        1 => 1,
        2 => 1,
        3 => 1,
        4 => -1,
        5 => 1,
        6 => 1,
        7 => 1,
        8 => -1,
        9 => -1,
        0 => -1,
        10 => 1,
        11 => -1,
        12 => -1,
        13 => -1,
        14 => -1,
        15 => 1,
        16 => -1,
        17 => 1,
        18 => -1,
        19 => 1,
        22 => 1,
        33 => 1,
        44 => -1,
        55 => -1,
        66 => -1,
        77 => 0,
        88 => -1,
        99 => -1,
    ];

    public const UNKNOWN_RATE = -2;

    /**
     * @param $number
     * @return int
     */
    public static function getRate($number): int
    {
        if (array_key_exists($number, Words::NUMBERS_RATE)) {
            return Words::NUMBERS_RATE[$number];
        }
        return self::UNKNOWN_RATE;
    }

    /**
     * @param string $chr
     * @return int
     */
    public static function getNumber(string $chr): int
    {
        if (array_key_exists($chr, Words::LETTERS_TO_NUM)) {
            return Words::LETTERS_TO_NUM[$chr];
        }
        return 0;
    }

}