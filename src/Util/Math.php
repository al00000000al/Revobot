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
      //  $filtered_input = array_filter($input);
        return (int)((int)array_sum($input) / count($input));
    }

    /**
     * @param $input
     * @return int
     */
    public static function sum($input):int{
        return (int)array_sum($input);
    }

}