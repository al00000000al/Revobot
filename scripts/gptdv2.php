<?php

require __DIR__ . '/../vendor/autoload.php';

use Revobot\Config;
use Revobot\Services\Providers\Tg;

require __DIR__ . '/../config.php';


if ($argc < 1) {
    echo "Идентификатор пользователя не передан.\n";
    exit(1);
}

$chat_id = (int)$argv[1];
$message_id = (int)$argv[2];

$base_path = Config::get('base_path');
$imageContent = file_get_contents($base_path . 'temp.jpg');
$base64Image = base64_encode($imageContent);

$data = [
    'model' => 'gpt-4-turbo',
    'messages' => [
        [
            'role' => 'user',
            'content' => [
                [
                    'type' => 'text',
                    'text' => "Это опасно? Ответь кратко"
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
