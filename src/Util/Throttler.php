<?php

namespace Revobot\Util;

use Revobot\Revobot;

class Throttler
{
    const PMC_THROTTLE_KEY = 'throttle_';

    public static function check(Revobot $bot, $user_id, $section = 'global', $limit, $interval)
    {
        $key = self::getKey($user_id, $section);
        $attempts = (int)$bot->pmc->get($key);

        if ($attempts === false) {
            // Ключ не существует, значит это первый запрос.
            // Устанавливаем счетчик в 1 и задаем время истечения TTL (Time-To-Live).
            $bot->pmc->set($key, 1, false, $interval);
            return false;
        } else if ($attempts < $limit) {
            $bot->pmc->increment($key);
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
