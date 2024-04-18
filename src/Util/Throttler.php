<?php

namespace Revobot\Util;

use Revobot\Revobot;

class Throttler
{
    const PMC_THROTTLE_KEY = 'throttle_';
    const PMC_BLOCK_KEY = 'block_'; // Добавлен новый ключ для блокировки

    public static function check($user_id, $section = 'global', $attempts_limit = 5, $interval = 12 * 60 * 60, $block_duration = 12 * 60 * 60)
    {
        $key = self::getKey($user_id, $section);
        $blockKey = self::getBlockKey($user_id, $section);
        $blockReleaseTime = PMC::get($blockKey);

        $currentTime = time();

        // Проверяем, не заблокирован ли пользователь и не истекло ли время блокировки
        if ($blockReleaseTime && $currentTime < $blockReleaseTime) {
            return true; // Пользователь заблокирован
        }

        // Если время блокировки истекло, сбросить количество попыток
        if ($blockReleaseTime && $currentTime >= $blockReleaseTime) {
            PMC::set($key, 0, 0, $interval);  // Сбрасываем счетчик попыток
        }

        $attempts = PMC::get($key);

        if (!$attempts) {
            // Ключ не существует или счетчик попыток сброшен, это первый запрос. Устанавливаем счетчик в 1.
            PMC::set($key, 1, 0, $interval);
            return false;
        } elseif ((int)$attempts < $attempts_limit) {
            // Увеличиваем количество попыток, если они не превышены
            PMC::increment($key);
            return false;
        } else {
            // Превышено количество попыток, устанавливаем время разблокировки
            $releaseTime = $currentTime + $block_duration;
            PMC::set($blockKey, $releaseTime, 0, $block_duration);
            return true;
        }
    }

    private static function getKey($user_id, $section)
    {
        return self::PMC_THROTTLE_KEY . $user_id . '_' . $section;
    }

    private static function getBlockKey($user_id, $section)
    {
        // Метод для получения ключа блокировки
        return self::PMC_BLOCK_KEY . $user_id . '_' . $section;
    }
}
