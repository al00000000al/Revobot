<?php

namespace Revobot\Numerology;

class Converter
{

    /**
     * @param string $word
     * @return int
     */
    public static function toNumber(string $word): int
    {

        $word = strtolower(trim($word));
        $word_len = mb_strlen($word);
        $number = 0;

        for ($i = 0; $i < $word_len; $i++) {
            $char = mb_substr($word, $i, 1, 'UTF-8');
            $char_n = Words::getNumber($char);
            $number += $char_n;
        }


        // get rate of special numbers
        $rate = Words::getRate($number);
        if ($rate !== Words::UNKNOWN_RATE) {
            return $rate;
        }

        // too long word
        if ($number >= 20) {
            return $number % 20;
        }

        // 12 = 1+2 = 3
        if ($number >= 10) {
            $left = (int)($number / 10);
            $right = $number % 10;
            $number = $left + $right;
        }

        // rate of other numbers
        return Words::getRate($number);
    }
}
