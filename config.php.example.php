<?php

$config['tg_key'] = '';
$config['vk_key'] = '';
$config['secret_key'] = '';
$config['fc_key'] = '';
$config['huggingface_key'] = '';
$config['tlgrm_typesense_key'] = '';
$config['open_weather_map_api_key'] = '';
$config['openai_api_key'] = '';

$config['tg_bot_admins'] = [12345];
$config['tg_bot_id'] = 12345;

$config['dl_video_api_url'] = 'https://';
$config['dl_video_api_key'] = '';


// openai in other commands:
$config['use_ai_cmd'] = false;

//payments crystalpay
$config += [
    'crystalpay_login' => '',
    'crystalpay_secret_key' => '',
    'crystalpay_salt' => '',
    'crystalpay_callback' => 'http://',
];
$config['openai_api_host'] = 'http://localhost/openai_api.php';
$config['stable_diffusion_task_api'] = 'http://localhost/sd_task?';

$config['base_path'] = '/home/opc/www/revobot/';
