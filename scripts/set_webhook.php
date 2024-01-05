<?php

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../config.php';

use Revobot\Config;
use Revobot\Services\Providers\Tg;


$url = Config::get('telegram_webhook_url');
Tg::setWebhook($url, ['secret_token' => Config::get('tg_secret_token')]);
echo "Webhook {$url} установлен!\r\n";
