<?php

use Revobot\Config;

/**
 * Получение ID пользователя из глобального экземпляра Revobot.
 *
 * @return int
 */
function userId(): int
{
    /** @var Revobot\Revobot $Bot */
    global $Bot;

    if ($Bot->provider === 'tg' && isset($Bot->raw_data['from']['id'])) {
        return (int)$Bot->raw_data['from']['id'];
    }

    if ($Bot->provider === 'vk' && isset($Bot->raw_data['from']['id'])) {
        return (int)$Bot->raw_data['from_id'];
    }

    return 0;
}

/**
 * Получение ID чата из глобального экземпляра Revobot.
 *
 * @return int
 */
function chatId(): int
{
    /** @var Revobot\Revobot $Bot */
    global $Bot;

    return (int)$Bot->chat_id;
}

/**
 * Получение типа сервиса ('tg', 'vk')
 *
 * @return string
 */
function provider(): string
{
    /** @var Revobot\Revobot $Bot */
    global $Bot;
    return $Bot->provider;
}

/**
 * @return bool
 */
function isAdmin($user): bool
{
    /** @var Revobot\Revobot $Bot */
    global $Bot;
    if ($Bot->provider === 'tg') {
        return in_array($user, Config::getArr('tg_bot_admins'), true);
    } elseif ($Bot->provider === 'vk') {
        return in_array($user, Config::getArr('vk_bot_admins'), true);
    }
    return false;
}

/**
 * @var mixed $data
 */
function debugLog($data)
{
    global $Debug;

    $Debug .= (string)$data . PHP_EOL;
}

#ifndef KPHP
if (!function_exists('cp1251')) {
    function cp1251(string $utf8_string)
    {
        return iconv('UTF-8', 'cp1251', $utf8_string);
    }
}
#endif
