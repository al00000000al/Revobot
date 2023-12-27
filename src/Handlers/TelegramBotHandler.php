<?php

namespace Revobot\Handlers;

use Revobot\RequestHandlerInterface;
use Revobot\Revobot;
use Revobot\Config;
use Revobot\Services\Providers\Tg;

class TelegramBotHandler implements RequestHandlerInterface
{
    /** @kphp-required */
    public function handle($uri)
    {
        // Получение входящих данных от Telegram
        $data = file_get_contents('php://input');
        $dataArr = (array)json_decode($data, true);
        if (!$dataArr) {
            return; // Если данные не удалось декодировать, прекращаем обработку
        }

        $bot = new Revobot('tg');
        $bot->setTgKey(Config::get('tg_key'));

        // Обработка обычных сообщений
        if (isset($dataArr['message'])) {
            $this->_handleMessage((array)$dataArr['message'], $bot);
        }

        // Обработка callback запросов (например, от inline кнопок)
        if (isset($dataArr['callback_query'])) {
            $this->_handleCallbackQuery($dataArr['callback_query'], $bot);
        }
    }

    private function _handleMessage($message, Revobot $bot)
    {
        if (isset($message['chat']['id'])) {
            $chatId = $message['chat']['id'];
            $bot->setChatId((int)$chatId);

            if (isset($message['text'])) {
                $bot->setMessage((string)$message['text']);
            } elseif (isset($message['photo'])) {
                $photoMessage = isset($message['caption']) ? $message['caption'] : '';
                $bot->setMessage((string)$photoMessage);
            }
            $bot->setRawData($message);
            $bot->run();
        }
    }

    private function _handleCallbackQuery($callbackQuery, Revobot $bot)
    {
        $chatId = (int)$callbackQuery["message"]["chat"]["id"];
        $callbackQueryId = (int)$callbackQuery["id"];
        $data = $callbackQuery["data"]; // данные, отправленные кнопкой

        $bot->setChatId($chatId);
        $bot->setMessage((string)$data);

        if (isset($callbackQuery['message'])) {
            $bot->setRawData($callbackQuery['message']);
            $bot->run();
        }

        // Метод для ответа на callback запрос, чтобы уведомить Telegram о его получении
        Tg::answerCallbackQuery($callbackQueryId);
    }
}
