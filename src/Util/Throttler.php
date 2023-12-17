<?php

namespace Revobot\Util;

use Revobot\Revobot;

class Throttler
{
    const PMC_THROTTLE_KEY = 'throttle_';

    public static function check(Revobot $bot, $user_id, $section = 'global', $max_per_minute = 60) {
        $key = self::getKey($user_id, $section);
        $value = (int)$bot->pmc->get($key);

        if ($value === 0) {
            $bot->pmc->set($key, 1, 60);
        } elseif ($value >= $max_per_minute) {
            return false;
        } else {
            $bot->pmc->increment($key);
        }

        return true;
    }

    private static function getKey($user_id, $section) {
        return self::PMC_THROTTLE_KEY.$user_id.'_'.$section;
    }
}
