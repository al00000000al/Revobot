<?php

use Revobot\Services\Dalle;
use Revobot\Services\Providers\Tg;

require __DIR__ . '/vendor/autoload.php';

require 'config.php';

global $PMC;
$PMC = new Memcache;
$PMC->addServer('127.0.0.1', 11209);

if ($argc < 3) {
    echo "Идентификатор пользователя не передан.\n";
    exit(1);
}

$user_id = (int)$argv[1];
$chat_id = (int)$argv[2];

$input = (string)$PMC->get('dalle_input'.$user_id);
if(!$input) {
    die;
}
list($status, $result) = Dalle::generate($this->input);
if($status === -1) {
    Tg::send($result, $chat_id);
} else {
    Tg::sendPhoto($chat_id, $result, $input);
}
