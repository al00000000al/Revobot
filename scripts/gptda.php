<?php

require __DIR__ . '/../vendor/autoload.php';

use Revobot\Config;
use Revobot\Services\Providers\Tg;
use Revobot\Util\PMC;

require __DIR__ . '/../config.php';

const PMC_USER_AI_INPUT_KEY = 'pmc_user_ai_input_';

if ($argc < 1) {
    echo "Идентификатор пользователя не передан.\n";
    exit(1);
}

$chat_id = (int)$argv[1];
$user_id = (int)$argv[2];
$message_id = (int)$argv[3];

$base_path = Config::get('base_path');
$imageContent = file_get_contents($base_path . 'temp.jpg');
$base64Image = base64_encode($imageContent);

$input = getInput($user_id);

$data = [
    'model' => 'gpt-4-turbo-preview',
    'messages' => [
        [
            'role' => 'user',
            'content' => $input,
        ]
    ],
    'max_tokens' => 400
];

$ch = curl_init(Config::get('openai_api_host'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['q' => json_encode($data), 'key' => Config::get('openai_api_key')]));
$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Ошибка cURL: ' . curl_error($ch);
    exit(1);
} else {
    $decodedResponse = json_decode($response, true);
}
curl_close($ch);

if (isset($decodedResponse['choices'][0]['message']['content'])) {
    $answer = $decodedResponse['choices'][0]['message']['content'];
    echo $answer . PHP_EOL;
    Tg::sendMessage($chat_id, $answer, 'markdown', ['reply_to_message_id' => $message_id]);
}


function getInput($user_id)
{
    $result = (string)(PMC::get(getInputKey($user_id)));
    return $result;
}
function getInputKey($user_id)
{
    return PMC_USER_AI_INPUT_KEY . 'tg' . $user_id;
}
