<?php

namespace Revobot\Services\Providers;

use Revobot\Config;
use Revobot\Util\Curl;

class Tg extends Base {

    public const API_URL = 'https://api.telegram.org/bot';

    public static function sendMessage(int $chat_id, string $text) {
        return self::_makeRequest('sendMessage', [
            'chat_id' => $chat_id,
            'text' => $text,
        ]);
    }

    public static function sendPoll(int $chat_id, string $question, array $options) {
        return self::_makeRequest('sendPoll', [
            'chat_id' => $chat_id,
            'question' => $question,
            'options' => json_encode($options),
        ]);
    }

    public static function sendChatAction(int $chat_id, string $action) {
        return self::_makeRequest('sendChatAction', [
            'chat_id' => $chat_id,
            'action' => $action,
        ]);
    }

    public static function getChatMember(int $chat_id, string $user_id) {
        return self::_makeRequest('getChatMember', [
            'chat_id' => $chat_id,
            'user_id' => $user_id,
        ]);
    }

    public static function setMyCommands(string $commands) {
        return self::_makeRequest('setMyCommands', [
            'commands' => $commands,
        ]);
    }

    private static function _getApiUrl(string $cmd){
        return self::API_URL . Config::get('tg_key') . '/' . $cmd;
    }

    private static function _makeRequest(string $cmd, array $data = []){
        return Curl::post(self::_getApiUrl($cmd), $data);
    }
}
