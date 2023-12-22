<?php

require __DIR__ . '/../vendor/autoload.php';

use Revobot\Games\AI\GptPMC;
use Revobot\Services\Providers\Tg;

require __DIR__ . '/../config.php';

if ($argc < 1) {
    echo "Идентификатор чата не передан.\n";
    exit(1);
}

$chat_id = (int)$argv[1];
$user_id = (int)$argv[2];

$GptPMC = new GptPMC($user_id, 'tg');
$history = $GptPMC->getHistory();

$result = '';
foreach ($history as $item) {
    $result .= '- ' . $item['role'] . ': ' . $item['content'] . "\n";
}

file_put_contents(__DIR__ . '/../tmp/history_' . $user_id . time() . '.md', $result);

$res = Tg::sendDocument($chat_id, '../tmp/history_' . $user_id . time() . '.md');
@unlink(__DIR__ . '/../tmp/history_' . $user_id . '_' . time() . '.md');
echo print_r($res);
