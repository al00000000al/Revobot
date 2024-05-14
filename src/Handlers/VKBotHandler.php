<?php

namespace Revobot\Handlers;

use Revobot\Config;
use Revobot\RequestHandlerInterface;

class VKBotHandler implements RequestHandlerInterface
{
    /** @kphp-required */
    public function handle($uri)
    {
        $data = file_get_contents('php://input');
        $dataArr = (array)json_decode($data, true);

        if (!$dataArr) {
            return;
        }

        if (!isset($dataArr['secret'])) {
            return;
        }

        if ($dataArr['secret'] !== Config::get('vk_bot_secret')) {
            return;
        }

        if (isset($dataArr['type'])) {
            switch ($dataArr['type']) {
                case 'confirmation':
                    if ((int)$dataArr['group_id'] === -Config::getInt('vk_bot_id')) {
                        return Config::get('vk_bot_confirmation');
                    }
                    break;

                case 'new_message':
                    $this->_handleNewMessage($dataArr['object']);
                    $this->_wrapOk();
                    break;
            }
        }
    }

    private function _handleNewMessage($messageData)
    {
        $userId = $messageData['from_id'];
        $messageText = $messageData['text'];
    }

    private function _sendMessage($userId, $text)
    {
    }

    private function _wrapOk()
    {
        header("HTTP/1.1 200 OK");
        echo 'ok';
    }
}
