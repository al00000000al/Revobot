<?php

namespace Revobot\Services\Providers;

// use CURLFile;
use Revobot\Config;
use Revobot\Util\Curl;

class Vk extends Base
{

    public const API_URL = 'https://api.vk.com/method/';

    public static function sendMessage(int $chat_id, string $text, $options = [])
    {
        $options['peer_id'] = $chat_id;
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
            'peer_id' => $chat_id,
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
        $options['need_json_decode'];
        return Curl::post(self::_getApiUrl($cmd), $data, $options);
    }

    public static function setActivity(int $chat_id, string $action, array $options = [])
    {
        return self::_makeRequest('messages.setActivity', [
            'peer_id' => $chat_id,
            'action' => $action,
            ...$options
        ]);
    }

    public static function getUsers(array $user_ids, array $options = [])
    {
        $res = self::_makeRequest('users.get', [
            'user_ids' => implode(',', $user_ids),
            ...$options
        ]);
        if (isset($res['response'])) {
            return $res['response'];
        } else {
            return $res;
        }
    }
}
