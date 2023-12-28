<?php

/**
 * Получение ID пользователя из глобального экземпляра Revobot.
 *
 * @return int
 */
function userId(): int
{
    global $Bot;
    if ($Bot->getProvider() === 'tg' && isset($Bot->getRawData()['from']['id'])) {
        return (int)$Bot->getRawData()['from']['id'];
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
    global $Bot;
    return $Bot->getChatId();
}
