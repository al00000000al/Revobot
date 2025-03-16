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
        // $options['disable_web_page_preview'] = true;

        $result = self::_makeRequest('sendMessage', [
            ...$options,
        ]);
        // dbg_echo(print_r($result, true));
        if (isset($result['error_code']) && (int)$result['error_code'] === 400) {
            $options['parse_mode'] = '';
            $result = self::_makeRequest('sendMessage', [
                ...$options,
            ]);
        }
        return $result;
    }

    public static function editMessageText(int $chat_id, int $message_id, string $text, string $parse_mode = null, array $options = [])
    {
        return self::_makeRequest('editMessageText', [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            'text' => $text,
            'parse_mode' => $parse_mode,
            'disable_web_page_preview' => true,
            ...$options,
        ]);
    }

    public static function editMessageReplyMarkup(int $chat_id, int $message_id, array $options = [])
    {
        return self::_makeRequest('editMessageReplyMarkup', [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            ...$options
        ]);
    }



    public static function sendPoll(int $chat_id, string $question, array $options, array $opts = [])
    {
        return self::_makeRequest('sendPoll', [
            'chat_id' => $chat_id,
            'question' => $question,
            'options' => json_encode($options),
            ...$opts
        ]);
    }

    public static function sendPhoto(int $chat_id, string $photo, string $caption = '', $options = [])
    {
        $options['chat_id'] = $chat_id;
        $options['photo'] = $photo;
        $options['caption'] = $caption;
        return self::_sendFile('photo', $options);
    }

    public static function sendAnimation(int $chat_id, string $animation, string $caption = '', $options = [])
    {
        $options['chat_id'] = $chat_id;
        $options['animation'] = $animation;
        $options['caption'] = $caption;
        return self::_sendFile('animation', $options);
    }

    public static function sendVideo(int $chat_id, string $video, string $caption = '', $options = [])
    {
        $options['chat_id'] = $chat_id;
        $options['video'] = $video;
        $options['caption'] = $caption;
        return self::_sendFile('video', $options);
    }

    public static function sendDocument(int $chat_id, string $document, string $caption = '', array $options = [])
    {
        $options['chat_id'] = $chat_id;
        $options['document'] = $document;
        $options['caption'] = $caption;
        return self::_sendFile('document', $options);
    }

    public static function sendAudio(int $chat_id, string $audio, string $caption = '', array $options = [])
    {
        $options['chat_id'] = $chat_id;
        $options['audio'] = $audio;
        $options['caption'] = $caption;
        return self::_sendFile('audio', $options);
    }

    public static function sendVoice(int $chat_id, string $voice, string $caption = '', array $options = [])
    {
        $options['chat_id'] = $chat_id;
        $options['voice'] = $voice;
        $options['caption'] = $caption;
        return self::_sendFile('voice', $options);
    }

    public static function sendLocation(int $chat_id, float $latitude, float $longitude, array $options = [])
    {
        return self::_makeRequest('sendLocation', [
            'chat_id' => $chat_id,
            'latitude' => $latitude,
            'longitude' => $longitude,
            ...$options
        ]);
    }

    public static function sendVenue(int $chat_id, float $latitude, float $longitude, string $title, string $address, array $options = [])
    {
        return self::_makeRequest('sendVenue', [
            'chat_id' => $chat_id,
            'latitude' => $latitude,
            'longitude' => $longitude,
            'title' => $title,
            'address' => $address,
            ...$options
        ]);
    }

    public static function sendContact(int $chat_id, string $phone_number, string $first_name, array $options = [])
    {
        return self::_makeRequest('sendContact', [
            'chat_id' => $chat_id,
            'phone_number' => $phone_number,
            'first_name' => $first_name,
            ...$options
        ]);
    }

    public static function sendDice(int $chat_id, array $options = [])
    {
        return self::_makeRequest('sendDice', [
            'chat_id' => $chat_id,
            ...$options
        ]);
    }

    private static function _sendFile(string $type, array $options = [])
    {
        if (!isset($options[$type])) {
            return [];
        }

        $media = (string)$options[$type];

        $is_url = substr($media, 0, 4) === 'http';
        if (isset($options['caption']) && $options['caption'] === 'Array') {
            $options['caption'] = '';
        }
        if (!$is_url) {
            #ifndef KPHP
            $options[$type] =  new CURLFile(realpath($media));
            #endif
            if (0) {
                $options[$type] = '@' . realpath($media);
            }
        }
        return self::_makeRequest('send' . ucfirst($type), $options, ['headers' => ['Content-Type:multipart/form-data']]);
    }



    public static function sendChatAction(int $chat_id, string $action, array $options = [])
    {
        return self::_makeRequest('sendChatAction', [
            'chat_id' => $chat_id,
            'action' => $action,
            ...$options
        ]);
    }

    public static function getUserProfilePhotos(int $user_id, array $options = [])
    {
        return self::_makeRequest('getUserProfilePhotos', [
            'user_id' => $user_id,
            ...$options
        ]);
    }

    public static function getChatMember(int $chat_id, string $user_id)
    {
        return self::_makeRequest('getChatMember', [
            'chat_id' => $chat_id,
            'user_id' => $user_id,
        ]);
    }

    public static function setMyCommands(string $commands, array $options = [])
    {
        return self::_makeRequest('setMyCommands', [
            'commands' => $commands,
            ...$options
        ]);
    }

    public static function getMyCommands(array $options = [])
    {
        return self::_makeRequest('getMyCommands', [
            ...$options
        ]);
    }

    public static function setChatMenuButton(array $options = [])
    {
        return self::_makeRequest('setChatMenuButton', [
            ...$options
        ]);
    }

    public static function getChatMenuButton(array $options = [])
    {
        return self::_makeRequest('getChatMenuButton', [
            ...$options
        ]);
    }

    public static function getMyDefaultAdministratorRights(array $options = [])
    {
        return self::_makeRequest('getMyDefaultAdministratorRights', [
            ...$options
        ]);
    }

    public static function stopPoll(int $chat_id, int $message_id, array $options = [])
    {
        return self::_makeRequest('stopPoll', [
            'chat_id' => $chat_id,
            'message_id' => $message_id,
            ...$options
        ]);
    }

    public static function deleteMessage(int $chat_id, int $message_id)
    {
        return self::_makeRequest('deleteMessage', [
            'chat_id' => $chat_id,
            'message_id' => $message_id
        ]);
    }


    public static function getFile(string $file_id)
    {
        return self::_makeRequest('getFile', [
            'file_id' => $file_id,
        ]);
    }

    public static function banChatMember(int $chat_id, int $user_id, array $options = [])
    {
        return self::_makeRequest('banChatMember', [
            'chat_id' => $chat_id,
            'user_id' => $user_id,
            ...$options
        ]);
    }
    public static function unbanChatMember(int $chat_id, int $user_id, array $options = [])
    {
        return self::_makeRequest('unbanChatMember', [
            'chat_id' => $chat_id,
            'user_id' => $user_id,
            ...$options
        ]);
    }

    public static function restrictChatMember(int $chat_id, int $user_id, string $permissions, array $options = [])
    {
        return self::_makeRequest('restrictChatMember', [
            'chat_id' => $chat_id,
            'user_id' => $user_id,
            'permissions' => $permissions,
            ...$options
        ]);
    }

    public static function getChatMemberCount(int $chat_id)
    {
        return self::_makeRequest('getChatMemberCount', [
            'chat_id' => $chat_id,
        ]);
    }

    public static function answerCallbackQuery(int $callback_query_id, array $options = [])
    {
        return self::_makeRequest('answerCallbackQuery', [
            'callback_query_id' => $callback_query_id,
            ...$options
        ]);
    }

    public static function getChatAdministrators(int $chat_id)
    {
        return self::_makeRequest('getChatAdministrators', [
            'chat_id' => $chat_id,
        ]);
    }

    public static function getChat(int $chat_id)
    {
        return self::_makeRequest('getChat', [
            'chat_id' => $chat_id,
        ]);
    }

    public static function setWebhook(string $url, $options = [])
    {
        return self::_makeRequest('setWebhook', [
            'url' => $url,
            'drop_pending_updates' => true,
            ...$options
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
