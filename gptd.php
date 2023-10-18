<?php

require __DIR__ . '/vendor/autoload.php';

use Revobot\Services\OpenAIService;
use Revobot\Services\Providers\Tg;

require 'config.php';

const PMC_USER_AI_KEY = 'pmc_user_ai_';
const PMC_USER_AI_HISTORY_KEY = 'pmc_user_ai_history_';
const PMC_USER_AI_INPUT_KEY = 'pmc_user_ai_input_';

global $PMC;
$PMC = new Memcache;
$PMC->addServer('127.0.0.1', 11209);

if ($argc < 3) {
    echo "Идентификатор пользователя не передан.\n";
    exit(1);
}

$user_id = (int)$argv[1];
$save_history = (bool)$argv[2];
$chat_id = (int)$argv[3];

// $user_id = 198239789;
$context = getContext($user_id);
$history = getHistory($user_id);
$input = getInput($user_id);

$current_date = date('Y-m-d H:i:s');
$date_message = ". Текущая дата: {$current_date}";

if(empty($context)){
    $context = "Ты полезный чат бот";
}

$context .= $date_message;
$answer = OpenAIService::generate($input, $context, $history, 'gpt-3.5-turbo');
if($save_history){
    $history = OpenAIService::addMessageToHistory($history, 'user', $input);
    $history = OpenAIService::addMessageToHistory($history, 'assistant', $answer);
    setHistory($history, $user_id);
}



echo $answer .PHP_EOL;
Tg::sendMessage($chat_id, $answer);

 function getContext($user_id){
    global $PMC;
    $result = (string) $PMC->get(getContextKey($user_id));
    return $result;
}

 function getHistory($user_id){
    global $PMC;
    $result = (array) json_decode($PMC->get(getHistoryKey($user_id)), true);
    return $result;
}

function getInput($user_id){
    global $PMC;
    $result = (string)($PMC->get(getInputKey($user_id)));
    return $result;
}

function setHistory(array $history, $user_id){
    global $PMC;
    $json_encoded = json_encode($history);
    $PMC->set(getHistoryKey($user_id), $json_encoded);
}

 function getContextKey($user_id){
    return PMC_USER_AI_KEY . 'tg' . $user_id;
}

 function getHistoryKey($user_id){
    return PMC_USER_AI_HISTORY_KEY . 'tg' . $user_id;
}

function getInputKey($user_id){
    return PMC_USER_AI_INPUT_KEY . 'tg' . $user_id;
}
