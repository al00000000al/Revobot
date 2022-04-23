<?php

namespace Revobot\Util;

class Math
{
    /**
     * @param $input
     * @return int
     */
    public static function avg($input): int
    {
        $filtered_input = array_filter($input);
        return (int)(array_sum($filtered_input) / count($filtered_input));
    }

}