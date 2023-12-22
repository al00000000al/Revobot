<?php

use Revobot\Config;
use Revobot\Services\Providers\Tg;

require_once '../vendor/autoload.php';
require_once '../config.php';


$input = 'Расскажи интересную историю';
$prompt = $input;
$chat_id = '198239789';
$messageId = -1;

$data = array(
    'model' => 'gpt-3.5-turbo',
    'stream' => true,
    'messages' => array(
        array(
            'role' => 'user',
            'content' => $prompt
        )
    )
);

$ch = curl_init();
$accumulatedData = '';
$chunkCount = 0;

$options = array(
    CURLOPT_URL => 'https://api.openai.com/v1/chat/completions',
    CURLOPT_RETURNTRANSFER => false,
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_HTTPHEADER => array(
        'Content-Type: application/json',
        'Authorization: Bearer ' . Config::get('openai_api_key')
    ),


    CURLOPT_WRITEFUNCTION => function ($ch, $chunk) use (&$accumulatedData, &$chunkCount, &$chat_id, &$messageId) {
        $ochunk = explode("\n", $chunk);
        foreach ($ochunk as $line) {
            if (empty(trim($line))) {
                continue;
            }
            $achunk = substr($line, 6);
            $xxchunk = json_decode($achunk, true);
            if (isset($xxchunk['choices'][0]['delta']['content'])) {
                $accumulatedData .= $xxchunk['choices'][0]['delta']['content'];
                $chunkCount++;
                if ($chunkCount % 5 == 0) {
                    if ($messageId === -1) {
                        $res = Tg::sendMessage($chat_id, $accumulatedData);
                        if (isset($res['result']['message_id'])) {
                            $messageId = $res['result']['message_id'];
                        }
                    } else {
                        Tg::editMessageText($chat_id, $messageId, $accumulatedData);
                    }
                }
            }
        }

        return strlen($chunk);
    }
);


curl_setopt_array($ch, $options);
curl_exec($ch);
curl_close($ch);
Tg::editMessageText($chat_id, $messageId, $accumulatedData);
