<?php

require __DIR__ . '/../vendor/autoload.php';

use Revobot\Config;

require '../config.php';


$data = [
    'model' => 'gpt-4-vision-preview',
    'messages' => [
        [
            'role' => 'user',
            'content' => [
                [
                    'type' => 'text',
                    'text' => "Write in detail what is in the picture, if there are trains, then what type and model of train with numbers, if buildings, then what types of buildings with numbers, if there are plants, then what types of plants. If you can determine country, write country. It maybe russia. Response in the format JSON: {\"keywords\":[]}"
                ],
                [
                    'type' => 'image_url',
                    'image_url' => ['url' => "https://upload.wikimedia.org/wikipedia/commons/thumb/e/e0/81-717-714_on_Nagatinsky_Metro_Bridge.jpg/800px-81-717-714_on_Nagatinsky_Metro_Bridge.jpg", 'detail' => 'high']
                ]
            ]
        ]
    ],
    'max_tokens' => 300
];

$ch = curl_init('https://api.openai.com/v1/chat/completions');
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Content-Type: application/json',
    'Authorization: Bearer ' . Config::get('openai_api_key')
]);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
$response = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Ошибка cURL: ' . curl_error($ch);
    exit(1);
} else {
    $decodedResponse = json_decode($response, true);
}
curl_close($ch);

print_r($decodedResponse);

if (isset($decodedResponse['choices'][0]['message']['content'])) {
    $answer = $decodedResponse['choices'][0]['message']['content'];
    echo $answer . PHP_EOL;
}
