<?php

require __DIR__ . '/../vendor/autoload.php';

use Revobot\Config;
use Revobot\Services\Providers\Tg;
use Revobot\Util\Curl;
use Revobot\Util\Strings;

require __DIR__ . '/../config.php';

while (true) {
    sdiRun();
    sleep(1);
}


function sdiGenerate($taskData)
{
    $user_id = $taskData['user_id'];
    $chat_id = $taskData['chat_id'];
    $prompt = $taskData['prompt'];
    if (!isset($taskData['photo'])) {
        require_once 'sd_script.php';
        sdGenerate($taskData);
        return;
    }
    $photo = $taskData['photo'];

    print_r($taskData);
    $input = 'tmp/input_img2img_' . time() . '_' . $user_id . '.png';
    file_put_contents($input, Tg::file($photo));

    // URL второго API для генерации изображения
    $imageApiUrl = "http://127.0.0.1:7860/sdapi/v1/img2img";
    $options = Strings::parseCommandArguments($prompt);
    $prompt = Strings::cleanCommandArguments($prompt);
    list($options, $prompt) = sdiSafe($prompt, $options);
    if (!isset($options['steps']) || (int)$options['steps'] > 50 || (int)$options['steps'] <= 1) {
        $options['steps'] = 8;
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
        "prompt" => $prompt . '  <lora:detail_slider_v4:1> <lora:add_sharpness:2>',
        'negative_prompt' => $options['negative_prompt'] . ' realisticvision-negative-embedding',
        "sampler_name" => 'DPM++ 3M SDE',
        'cfg_scale' => 6,
        'sampler_index' => 'DPM++ 3M SDE',
        'init_images' => [Strings::img2base64($input)],
        'denoising_strength' => 0.5,
        'inpainting_fill' => 0,
        'inpaint_full_res' => false,
        'inpaint_full_res_padding' => 0,
        'batch_size' => 1,
        'n_iter' => 1,

        ...$options
    );

    $responseData = Curl::post($imageApiUrl, json_encode($payload), ['headers' => ['Content-Type:application/json'], 'need_json_decode' => true]);

    print_r($responseData);

    if (isset($responseData['images'][0])) {
        $imageData = base64_decode($responseData['images'][0]);
        $output = '../tmp/output_img2img_' . time() . '_' . $user_id . '.png';
        file_put_contents($output, $imageData);
        Tg::sendPhoto($chat_id, $output, $taskData['prompt'], ['has_spoiler' => 1]);
        // @unlink($output);
    } else {
        echo "Пока ничего нет";
    }
}

function sdiRun()
{
    $taskApiUrl = Config::get('stable_diffusion_task_api') . http_build_query(['key' => Config::get('stable_diffusion_task_key')]);
    $taskData = json_decode(Curl::get($taskApiUrl), true);

    if (!isset($taskData['prompt'])) {
        echo date(DATE_ATOM, time()) . "\r\n";
        return;
    }
    sdiGenerate($taskData);
}

function sdiSafe($prompt, $options)
{
    $negative_prompt = '';
    $options['negative_prompt'] = 'nude child, naked child, nude young girl, nude young boy, child face, woman, girl';
    if (str_contains($prompt, '<lora:y') || str_contains($prompt, '<lora:m') || str_contains($prompt, 'child')  || str_contains($prompt, 'young') || str_contains($prompt, 'kinder')  || str_contains($prompt, 'little')   || str_contains($prompt, 'little') || str_contains($prompt, 'boy')  || str_contains($prompt, 'small') || str_contains($prompt, 'kid')) {
        $negative_prompt = 'nsfw, nude, naked, nude child, naked child, nude young girl, nude young boy, child face, woman, girl';
        $options['negative_prompt'] = $negative_prompt;
        for ($i = 0; $i <= 10; $i++) {
            $prompt = _sdiReplace($prompt);
        }
    }
    return [$options, $prompt];
}

function _sdiReplace($prompt)
{
    return str_replace(['nude', 'naked', 'penis', 'dick', 'bath', 'shower', 'tits', 'porn'], [''], $prompt);
}
