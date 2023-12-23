<?php

namespace Revobot\Services\Providers;

use CURLFile;
use Revobot\Config;
use Revobot\Util\Curl;

class Tg extends Base
{

    public const API_URL = 'https://api.telegram.org/bot';
    public const API_FILE_URL = 'https://api.telegram.org/file/bot';

    public static function sendMessage(int $chat_id, string $text, string $parse_mode = null, $options = [])
    {
        $options['chat_id'] = $chat_id;
        $options['text'] = $text;
        $options['parse_mode'] = $parse_mode;
        $options['disable_web_page_preview'] = true;

        $result = self::_makeRequest('sendMessage', [
            ...$options,
        ]);
        dbg_echo(print_r($result, true));
        if (isset($result['error_code']) && (int)$result['error_code'] === 400) {
            $options['parse_mode'] = '';
            $result = self::_makeRequest('sendMessage', [
                ...$options,
            ]);
        }
        return $result;
    }

    public static function editMessageText(int $chat_id, int $message_id, string $text, string $parse_mode = null)
    {
        return self::_makeRequest('editMessageText', [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'text' => $text,
            'parse_mode' => $parse_mode,
            'disable_web_page_preview' => true,
        ]);
    }

    public static function sendPoll(int $chat_id, string $question, array $options)
    {
        return self::_makeRequest('sendPoll', [
            'chat_id' => $chat_id,
            'question' => $question,
            'options' => json_encode($options),
        ]);
    }

    public static function sendPhoto(int $chat_id, string $photo, string $caption = '', $options = [])
    {
        $is_url = substr($photo, 0, 4) === 'http';
        if ($caption === 'Array') {
            $caption = '';
        }
        if (!$is_url) {
            #ifndef KPHP
            $photo =  new CURLFile(realpath($photo));
            #endif
            if (0) {
                $photo = '@' . realpath($photo);
            }
        }
        return self::_makeRequest('sendPhoto', [
            'chat_id' => $chat_id,
            'photo' => $photo,
            'caption' => $caption,
            ...$options,
        ], ['headers' => ['Content-Type:multipart/form-data']]);
    }

    public static function sendAnimation(int $chat_id, string $animation, string $caption = '', $options = [])
    {
        $is_url = substr($animation, 0, 4) === 'http';
        if ($caption === 'Array') {
            $caption = '';
        }
        if (!$is_url) {
            #ifndef KPHP
            $animation =  new CURLFile(realpath($animation));
            #endif
            if (0) {
                $animation = '@' . realpath($animation);
            }
        }
        return self::_makeRequest('sendAnimation', [
            'chat_id' => $chat_id,
            'animation' => $animation,
            'caption' => $caption,
            ...$options,
        ], ['headers' => ['Content-Type:multipart/form-data']]);
    }

    public static function sendVideo(int $chat_id, string $video, string $caption = '', $options = [])
    {
        $is_url = substr($video, 0, 4) === 'http';
        if ($caption === 'Array') {
            $caption = '';
        }
        if (!$is_url) {
            #ifndef KPHP
            $animation =  new CURLFile(realpath($video));
            #endif
            if (0) {
                $animation = '@' . realpath($video);
            }
        }
        return self::_makeRequest('sendVideo', [
            'chat_id' => $chat_id,
            'video' => $video,
            'caption' => $caption,
            ...$options,
        ], ['headers' => ['Content-Type:multipart/form-data']]);
    }

    public static function sendDocument(int $chat_id, string $document)
    {
        #ifndef KPHP
        return self::_makeRequest('sendDocument', [
            'chat_id' => $chat_id,
            'document' => new CURLFile(realpath($document)),
        ], ['headers' => ['Content-Type:multipart/form-data']]);
        #endif
        if (0) {
            return self::_makeRequest('sendDocument', [
                'chat_id' => $chat_id,
                'document' => '@' . (realpath($document)),
            ], ['headers' => ['Content-Type:multipart/form-data']]);
        }
    }

    public static function sendChatAction(int $chat_id, string $action)
    {
        return self::_makeRequest('sendChatAction', [
            'chat_id' => $chat_id,
            'action' => $action,
        ]);
    }

    public static function getChatMember(int $chat_id, string $user_id)
    {
        return self::_makeRequest('getChatMember', [
            'chat_id' => $chat_id,
            'user_id' => $user_id,
        ]);
    }

    public static function setMyCommands(string $commands)
    {
        return self::_makeRequest('setMyCommands', [
            'commands' => $commands,
        ]);
    }

    public static function getFile(string $file_id)
    {
        return self::_makeRequest('getFile', [
            'file_id' => $file_id,
        ]);
    }

    public static function file(string $file_path)
    {
        return Curl::get(self::API_FILE_URL . Config::get('tg_key') . '/' . $file_path);
    }

    private static function _getApiUrl(string $cmd)
    {
        return self::API_URL . Config::get('tg_key') . '/' . $cmd;
    }

    private static function _makeRequest(string $cmd, array $data = [], $options = [])
    {
        $options['need_json_decode'] = true;
        return Curl::post(self::_getApiUrl($cmd), $data, $options);
    }
}
