<?php

namespace Revobot\Services\Providers;

// use CURLFile;
use Revobot\Config;
use Revobot\Util\Curl;

class Tg extends Base
{

    public const API_URL = 'https://api.vk.com/method/';

    public static function sendMessage(int $chat_id, string $text, $options = [])
    {
        $options['chat_id'] = $chat_id;
        $options['text'] = $text;
        // $options['disable_web_page_preview'] = true;

        $result = self::_makeRequest('messages.send', [
            ...$options,
        ]);
        return $result;
    }

    public static function sendPhoto(int $chat_id, string $photo, string $caption = '', $options = [])
    {
        // TODO
    }


    public static function deleteMessage(int $chat_id, int $message_id)
    {
        return self::_makeRequest('messages.delete', [
            'chat_id' => $chat_id,
            'message_id' => $message_id
        ]);
    }


    private static function _getApiUrl(string $cmd)
    {
        return self::API_URL . '/' . $cmd;
    }

    private static function _makeRequest(string $cmd, array $data = [], $options = [])
    {
        $data['access_token'] = Config::get('vk_key');
        $data['v'] = '5.133';
        return Curl::post(self::_getApiUrl($cmd), $data, $options);
    }
}
