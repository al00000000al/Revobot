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
        'q'=>9,'w'=>6,'e'=>7,'r'=>3,'t'=>2,'y'=>9,'u'=>1,'i'=>2,'a'=>1,
        's'=>6,'d'=>4,'f'=>1,'g'=>7,'h'=>4,'j'=>8,'k'=>9,'l'=>6,'z'=>5,
        'x'=>4,'c'=>3,'v'=>6,'b'=>5,'n'=>4,'m'=>3,'!'=>1,'?'=>2,'.'=>3,','=>4,
        '0' => 0,'1' => 1,'2' =>2,'3' =>3,'4'=>4,'5'=>5,'6'=>6,'7'=>7,'8'=>8,'9'=>9,
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
        0 => 0,
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
        77 => 1,
        88 => -1,
        99 => -1,
    ];

    public const UNKNOWN_RATE = -2;


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
