<?php


namespace Revobot\Games\Predictor;


class Utils
{
    /**
     * @param int $user_id
     * @param string $input
     * @return string
     */
    public static function replaceMe(int $user_id, string $input): string
    {
        $input = str_replace(['me','my','мне','мое','мою','моё','меня'], $user_id.'', $input);
        return (string)str_replace(['I ','я '], $user_id.' ', $input);
    }

    /**
     * @param string $input
     * @return string
     */
    public static function replaceDate(string $input): string
    {
        $today = date('d.m.Y');
        $tomorrow = date('d.m.Y', strtotime('+1 day'));
        $input = str_replace(['today','now','сейчас','сегодня'], $today, $input);
        return (string)str_replace(['tomorrow','завтра'], $tomorrow, $input);
    }
}
