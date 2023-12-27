<?php

namespace Revobot\Handlers;

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

        if (isset($dataArr['type']) && $dataArr['type'] === 'message_new') {
            $this->_handleNewMessage($dataArr['object']);
        }

        echo 'ok';
    }

    private function _handleNewMessage($messageData)
    {
        $userId = $messageData['from_id'];
        $messageText = $messageData['text'];
    }

    private function _sendMessage($userId, $text)
    {
    }
}
