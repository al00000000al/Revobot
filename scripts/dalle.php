<?php

use Revobot\Services\Dalle;
use Revobot\Services\Providers\Tg;
use Revobot\Util\PMC;

require __DIR__ . '/../vendor/autoload.php';

require  __DIR__ . '/../config.php';

if ($argc < 3) {
    echo "Идентификатор пользователя не передан.\n";
    exit(1);
}

$user_id = (int)$argv[1];
$chat_id = (int)$argv[2];

$input = (string)PMC::get('dalle_input' . $user_id);
if (!$input) {
    die;
}
list($status, $result) = Dalle::generate($input);
if ($status === -1) {
    Tg::sendMessage($chat_id, $result);
} else {
    Tg::sendPhoto($chat_id, $result, $input);
}
