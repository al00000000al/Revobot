<?php

namespace Revobot\Util;

use Revobot\Revobot;

class Throttler
{
    const PMC_THROTTLE_KEY = 'throttle_';

    public static function check(Revobot $bot, $user_id, $section = 'global', $limit = 1, $interval = 60 * 60)
    {
        $key = self::getKey($user_id, $section);
        $attempts = PMC::get($key);

        if (!$attempts) {
            // Ключ не существует, значит это первый запрос.
            // Устанавливаем счетчик в 1 и задаем время истечения TTL (Time-To-Live).
            PMC::set($key, 1, 0, $interval);
            return false;
        } else if ((int)$attempts < $limit) {
            PMC::increment($key);
            return false;
        } else {
            return true;
        }
    }

    private static function getKey($user_id, $section)
    {
        return self::PMC_THROTTLE_KEY . $user_id . '_' . $section;
    }
}
