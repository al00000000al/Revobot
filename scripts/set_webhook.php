<?php

require __DIR__ . '/../vendor/autoload.php';

use Revobot\Config;
use Revobot\Services\Providers\Tg;

require __DIR__ . '/../config.php';

Tg::setWebhook(Config::get('telegram_webhook_url'));

echo "Webhook {$url} установлен!\r\n";
