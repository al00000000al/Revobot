<?php

namespace Revobot\Util;

class Time
{

    const GARMONIC = 86.4; // Один гармоник равен 86.4 секунды

    public static function today()
    {
        return date('Ymd');
    }

    /**
     * Гармоническое время
     */
    public static function garmonic($time)
    {
        // Получаем текущее время в секундах с начала дня
        $currentHour = date("G", $time);
        $currentMinute = date("i", $time);
        $currentSecond = date("s", $time);
        $secondsSinceMidnight = $currentHour * 3600 + $currentMinute * 60 + $currentSecond;
        // Один гармоник равен 86.4 секунды, поэтому делим на это число
        $harmonics = floor($secondsSinceMidnight / self::GARMONIC);
        return self::_getHarmonicCode($harmonics) . $harmonics;
    }

    // Определяем буквенный код гармонического времени
    private static function _getHarmonicCode($harmonics)
    {
        if ($harmonics < 200) {
            return 'R'; // Рассвет
        } elseif ($harmonics < 400) {
            return 'M'; // Утро
        } elseif ($harmonics < 700) {
            return 'D'; // День
        } elseif ($harmonics < 900) {
            return 'E'; // Вечер
        } else {
            return 'N'; // Ночь
        }
    }
}
