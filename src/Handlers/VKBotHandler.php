<?php

namespace Revobot\Handlers;

use Revobot\Config;
use Revobot\RequestHandlerInterface;
use Revobot\Response;
use Revobot\Revobot;

class VKBotHandler implements RequestHandlerInterface
{
    /** @kphp-required */
    public function handle($uri)
    {
        $data = file_get_contents('php://input');
        $dataArr = (array)json_decode($data, true);

        if (!$dataArr) {
            Response::json(['error' => 'invalid request']);
            return;
        }

        if (!isset($dataArr['secret'])) {
            Response::json(['error' => 'no secret']);
            return;
        }

        if ($dataArr['secret'] !== Config::get('vk_bot_secret')) {
            Response::json(['error' => 'invalid secret']);
            return;
        }

        if (isset($dataArr['type'])) {
            switch ($dataArr['type']) {
                case 'confirmation':
                    Response::text(Config::get('vk_bot_confirmation'));
                    break;

                case 'message_new':
                    $this->_handleNewMessage($dataArr['object']);
                    Response::text('ok');
                    break;
            }
        }
    }

    private function _handleNewMessage($messageData)
    {
        $message = $messageData['message'];

        $bot = new Revobot('vk');
        $bot->setVkKey(Config::get('vk_key'));

        if (isset($message['peer_id'])) {
            $chatId = $message['peer_id'];
            $bot->setChatId((int)$chatId);

            if (isset($message['text'])) {
                if ($message['text'] == 'Начать') {
                    $message['text'] = '/start';
                }
                $bot->setMessage((string)$message['text']);
            }
            $bot->setRawData($message);
            $bot->run();
        }
    }
}
