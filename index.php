<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';

use Revobot\Revobot;


$url = $_SERVER['PHP_SELF'];
if($url == '/tg_bot' || $url = '/vk_bot'){
    $data = file_get_contents('php://input');

    $data_arr = json_decode($data, true);

    if($url == 'tg_bot'){
        $chat_id = $data_arr['message']['chat']['chat_id'];
        $bot = new Revobot('tg');
        $bot->setChatId($chat_id);
        $bot->setMessage($data_arr['message']['text']);
        $bot->run();
    }
}


