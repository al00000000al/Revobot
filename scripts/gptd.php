<?php

require __DIR__ . '/../vendor/autoload.php';

use Revobot\Services\OpenAIService;
use Revobot\Services\Providers\Tg;
use Revobot\Util\PMC;

require __DIR__ . '/../config.php';
global $NeedProxy;

$NeedProxy = true;

const PMC_USER_AI_KEY = 'pmc_user_ai_';
const PMC_USER_AI_HISTORY_KEY = 'pmc_user_ai_history_';
const PMC_USER_AI_INPUT_KEY = 'pmc_user_ai_input_';

if ($argc < 3) {
    echo "Идентификатор пользователя не передан.\n";
    exit(1);
}

$user_id = (int)$argv[1];
$save_history = (bool)$argv[2];
$chat_id = (int)$argv[3];
$message_id = (int)$argv[4] ?? 0;

// $user_id = 198239789;
$context = getContext($user_id);
$history = getHistory($user_id);
$input = getInput($user_id);

$current_date = date('Y-m-d H:i:s');
$date_message = ". Текущая дата: {$current_date}";

if (empty($context)) {
    $context = "Ты полезный чат бот";
}

$context .= $date_message;


$answer = OpenAIService::generate($input, $context, $history, 'gpt-4-1106-preview'); //'gpt-3.5-turbo'
if ($save_history) {
    $history = OpenAIService::addMessageToHistory($history, 'user', $input);
    $history = OpenAIService::addMessageToHistory($history, 'assistant', $answer);
    setHistory($history, $user_id);
}

$continue = json_encode([
    [
        [
            'text' => 'Продолжить',
            'callback_data' => '/ии продолжи'
        ]
    ]
]);

echo $answer . PHP_EOL;
if ($message_id > 0) {
    $res = Tg::sendMessage($chat_id, $answer, 'markdown', ['reply_to_message_id' => $message_id, 'reply_markup' => $continue]);
    print_r($res);
} else {
    $res = Tg::sendMessage($chat_id, $answer, 'markdown', ['reply_markup' => $continue]);
    print_r($res);
}


function getContext($user_id)
{
    $result = (string) PMC::get(getContextKey($user_id));
    return $result;
}

function getHistory($user_id)
{
    $result = (array) json_decode(PMC::get(getHistoryKey($user_id)), true);
    return $result;
}

function getInput($user_id)
{
    $result = (string)(PMC::get(getInputKey($user_id)));
    return $result;
}

function setHistory(array $history, $user_id)
{
    $json_encoded = json_encode($history);
    PMC::set(getHistoryKey($user_id), $json_encoded, 0,);
}

function getContextKey($user_id)
{
    return PMC_USER_AI_KEY . 'tg' . $user_id;
}

function getHistoryKey($user_id)
{
    return PMC_USER_AI_HISTORY_KEY . 'tg' . $user_id;
}

function getInputKey($user_id)
{
    return PMC_USER_AI_INPUT_KEY . 'tg' . $user_id;
}
