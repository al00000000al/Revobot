<?php
require_once '../../vendor/autoload.php';
require_once '../../config.php';

use Revobot\Config;
use Revobot\Money\Revocoin;
use Revobot\Revobot;

$bot = new Revobot('tg');
$bot->setTgKey(Config::get('tg_key'));
$revocoin = new Revocoin($bot);

for ($i = 0; $i < 10000; $i++) {
    $mining_result = $revocoin->mining(-Config::get('tg_bot_id'), 0, time() . ' ' . mt_rand(PHP_INT_MIN, PHP_INT_MAX));
    if (!empty($mining_result)) {
        var_dump($mining_result);
    }
    // sleep(1);
}
