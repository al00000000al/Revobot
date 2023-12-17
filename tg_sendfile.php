<?php

require __DIR__ . '/vendor/autoload.php';

use Revobot\Games\AI\GptPMC;
use Revobot\Services\Providers\Tg;

require 'config.php';

if ($argc < 1) {
    echo "Идентификатор чата не передан.\n";
    exit(1);
}


global $pmc;
$pmc = new Memcache();
$pmc->addServer('127.0.0.1', 11209);

$chat_id = (int)$argv[1];
$user_id = (int)$argv[2];

$GptPMC = new GptPMC($user_id, 'tg');
$history = $GptPMC->getHistory();

$result = '';
foreach ($history as $item) {
    $result .= '- '.$item['role'] .': '.$item['content']."\n";
}

file_put_contents(__DIR__.'/history_'.$user_id.'.md', $result);

$res = Tg::sendDocument($chat_id, 'history_'.$user_id.'.md');
@unlink(__DIR__.'/history_'.$user_id.'_'.time().'.md');
echo print_r($res);
