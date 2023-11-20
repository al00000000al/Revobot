<?php

require __DIR__ . '/vendor/autoload.php';

use Revobot\Config;
use Revobot\Services\Providers\Tg;

require 'config.php';


global $PMC;
$PMC = new Memcache;
$PMC->addServer('127.0.0.1', 11209);

if ($argc < 1) {
    echo "Идентификатор пользователя не передан.\n";
    exit(1);
}

$chat_id = (int)$argv[1];


$imageContent = file_get_contents('/home/opc/www/revobot/temp.jpg');
$base64Image = base64_encode($imageContent);

$data = [
    'model' => 'gpt-4-vision-preview',
    'messages' => [
        [
            'role' => 'user',
            'content' => [
                [
                    'type' => 'text',
                    'text' => "Че тут? Напиши очень кратко"
                ],
                [
                    'type' => 'image_url',
                    'image_url' => ['url' => "data:image/jpeg;base64,$base64Image", 'detail' => 'low']
                ]
            ]
        ]
    ],
    'max_tokens' => 300
];

$ch = curl_init(Config::get('openai_api_host'));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['q' => json_encode($data), 'key' => Config::get('openai_api_key')]));
$response = curl_exec($ch);

/*

$ch = curl_init('https://api.openai.com/v1/chat/completions');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . Config::get('openai_api_key')
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
$response = curl_exec($ch);
*/

if (curl_errno($ch)) {
    echo 'Ошибка cURL: ' . curl_error($ch);
    exit(1);
} else {
    $decodedResponse = json_decode($response, true);
}
curl_close($ch);

if(isset($decodedResponse['choices'][0]['message']['content'])){
    $answer = $decodedResponse['choices'][0]['message']['content'];
    echo $answer .PHP_EOL;
    Tg::sendMessage($chat_id, $answer);
}
