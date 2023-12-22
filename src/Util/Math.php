<?php

namespace Revobot\Util;

class Math
{
    public static function avg($input): int
    {
        //  $filtered_input = array_filter($input);
        return (int)((int)array_sum($input) / count($input));
    }


    public static function sum(array $input): int
    {
        return (int)array_sum($input);
    }
}
