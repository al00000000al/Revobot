<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use Revobot\Services\Providers\Tg;
use Revobot\Util\PMC;

function processTimers()
{
    $current_time = time();
    $timer_keys = PMC::get('timer_#');



    if (empty($timer_keys)) {
        return;
    }

    foreach ($timer_keys as $key => $data) {
        $timer_info = json_decode($data, true);

        print_r($timer_info);

        echo "$current_time >= {$timer_info['datetime']}\r\n";

        if ((int)$current_time >= (int)$timer_info['datetime']) {
            $username = getUsername($timer_info['chat_id'], $timer_info['user']);
            Tg::sendMessage($timer_info['chat_id'], $username . ' ' . $timer_info['text']);
            PMC::delete('timer_' . $timer_info['datetime'] . '_' . $timer_info['_rnd']);
        }
    }
}

processTimers();

function getUsername(int $chat_id, int $user_id): string
{
    $chat_member = Tg::getChatMember($chat_id, $user_id);

    if (!isset($chat_member['result'])) {
        return '';
    }

    if (isset($chat_member['result']['user']['username'])) {
        $username = '@' . $chat_member['result']['user']['username'];
    } else {
        $username = (string)$user_id;
    }
    return $username;
}
