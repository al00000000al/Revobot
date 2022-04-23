<?php

namespace Revobot\Numerology;

class Converter
{

    /**
     * @param $word
     * @return int
     */
    public static function toNumber($word): int
    {
        $chrs = mb_str_split(mb_strtolower($word));
        $number = 0;

        foreach ($chrs as $chr) {
            $number += Words::getNumber($chr);
        }

        // too long word
        if ($number >= 100) {
            return 0;
        }

        // get rate of special numbers
        $rate = Words::getRate($number);

        if($rate !== Words::UNKNOWN_RATE) {
            return $rate;
        }

        // 12 = 1+2 = 3
        $left = (int)($number / 10);
        $right = $number % 10;
        $number = $left + $right;

        // rate of other numbers
        return Words::getRate($number);
    }


}