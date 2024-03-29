<?php

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
 * @return int
 */
function provider(): int
{
    /** @var Revobot\Revobot $Bot */
    global $Bot;
    return $Bot->provider;
}
