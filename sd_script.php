<?php

require __DIR__ . '/vendor/autoload.php';

use Revobot\Config;
use Revobot\Services\Providers\Tg;
use Revobot\Util\Curl;

require 'config.php';

while(true) {
    sdRun();
    sleep(2);
}


function sdGenerate($taskData) {
    $user_id = $taskData['user_id'];
    $chat_id = $taskData['chat_id'];
    $prompt = $taskData['prompt'];

    // URL второго API для генерации изображения
    $imageApiUrl = "http://127.0.0.1:7860/sdapi/v1/txt2img";

    $payload = array(
        "prompt" => $taskData['prompt'].' <lora:lcm-lora-sdv1-5:1>',
        "steps" => 8,
        "sampler_name" => 'LCM',
        'cfg_scale' => 1,
        'sampler_index' => 'LCM',
    );

    $responseData = Curl::post($imageApiUrl, json_encode($payload), ['headers' => ['Content-Type:application/json']]);

    if (isset($responseData['images'][0])) {
        $imageData = base64_decode($responseData['images'][0]);
        $output = 'tmp/output'.time().'.png';
        file_put_contents($output, $imageData);
        Tg::sendPhoto($chat_id, $output, $prompt, ['has_spoiler' => 1]);
        // @unlink($output);
    } else {
        echo "Ошибка: изображение не найдено в ответе.";
    }

}
function sdRun() {
    $taskApiUrl = Config::get('stable_diffusion_task_api').http_build_query(['key' => Config::get('stable_diffusion_task_key')]);
    $taskData = json_decode(Curl::get($taskApiUrl), true);

    if (!isset($taskData['prompt'])) {
        echo "Ошибка: задание не найдено в ответе.\r\n";
        return;
    }
    sdGenerate($taskData);
}
