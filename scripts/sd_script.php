<?php

require __DIR__ . '/../vendor/autoload.php';

use Revobot\Config;
use Revobot\Services\Providers\Tg;
use Revobot\Util\Curl;
use Revobot\Util\Strings;

global $NoCheck;

$NoCheck = true;

require __DIR__ . '/../config.php';

while (true) {
    sdRun();
    sleep(1);
}


function sdGenerate($taskData)
{
    $user_id = $taskData['user_id'];
    $chat_id = $taskData['chat_id'];
    $prompt = $taskData['prompt'];

    // URL второго API для генерации изображения
    $imageApiUrl = "http://127.0.0.1:7860/sdapi/v1/txt2img";
    $options = Strings::parseCommandArguments($prompt);
    $prompt = Strings::cleanCommandArguments($prompt);
    list($options, $prompt) = sdSafe($prompt, $options);
    if (!isset($options['steps']) || (int)$options['steps'] > 50 || (int)$options['steps'] <= 1) {
        $options['steps'] = 16;
    }
    if (isset($options['width'])) {
        if ((int)$options['width'] > 1024 || (int)$options['width'] < 8) {
            $options['width'] = 600;
        }
    } else {
        $options['width'] = 600;
    }
    if (isset($options['height'])) {
        if ((int)$options['height'] > 1024 || (int)$options['height'] < 8) {
            $options['height'] = 512;
        }
    } else {
        $options['height'] = 512;
    }
    $prompt = Strings::transliterate($prompt);
    $payload = array(
        "prompt" => $prompt . '',
        'negative_prompt' => $options['negative_prompt'] . ' realisticvision-negative-embedding',
        "sampler_name" => 'DPM++ 3M SDE',
        'cfg_scale' => 6,
        'sampler_index' => 'DPM++ 3M SDE',
        ...$options
    );

    $responseData = Curl::post($imageApiUrl, json_encode($payload), ['headers' => ['Content-Type:application/json'], 'need_json_decode' => true]);

    if (isset($responseData['images'][0])) {
        $imageData = base64_decode($responseData['images'][0]);
        $output = '../tmp/output' . time() . '_' . $user_id . '.png';
        file_put_contents($output, $imageData);
        Tg::sendPhoto($chat_id, $output, $taskData['prompt'], ['has_spoiler' => 1]);
        // @unlink($output);
    } else {
        echo "Пока ничего нет";
    }
}

function sdRun()
{
    $taskApiUrl = Config::get('stable_diffusion_task_api') . http_build_query(['key' => Config::get('stable_diffusion_task_key')]);
    $taskData = json_decode(Curl::get($taskApiUrl), true);

    print_r($taskData);

    if (!isset($taskData['prompt'])) {
        echo date(DATE_ATOM, time()) . "\r\n";
        return;
    }
    sdGenerate($taskData);
}

function sdSafe($prompt, $options)
{
    $negative_prompt = '';
    $options['negative_prompt'] = 'nude child, naked child, nude young girl, nude young boy, child face, woman, girl';
    if (str_contains($prompt, '<lora:y') || str_contains($prompt, '<lora:m') || str_contains($prompt, 'child')  || str_contains($prompt, 'young') || str_contains($prompt, 'kinder')  || str_contains($prompt, 'little')   || str_contains($prompt, 'little') || str_contains($prompt, 'boy')  || str_contains($prompt, 'small') || str_contains($prompt, 'kid')) {
        $negative_prompt = 'nsfw, nude, naked, nude child, naked child, nude young girl, nude young boy, child face, woman, girl';
        $options['negative_prompt'] = $negative_prompt;
        for ($i = 0; $i <= 10; $i++) {
            $prompt = _sdReplace($prompt);
        }
    }
    return [$options, $prompt];
}

function _sdReplace($prompt)
{
    return str_replace(['nude', 'naked', 'penis', 'dick', 'bath', 'shower', 'tits', 'porn'], [''], $prompt);
}
