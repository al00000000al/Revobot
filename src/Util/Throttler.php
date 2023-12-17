<?php

namespace Revobot\Util;

class Throttler
{
    const PMC_THROTTLE_KEY = 'throttle_';

    public static function check($user_id, $section = 'global', $max_per_minute = 60) {
        global $pmc;

        $key = self::getKey($user_id, $section);
        $value = (int)$pmc->get($key);

        if ($value === 0) {
            $pmc->set($key, 1, 60);
        } elseif ($value >= $max_per_minute) {
            return false;
        } else {
            $pmc->increment($key);
        }

        return true;
    }

    private static function getKey($user_id, $section) {
        return self::PMC_THROTTLE_KEY.$user_id.'_'.$section;
    }
}
