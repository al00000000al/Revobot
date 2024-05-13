<?php

use Revobot\Games\AI\Gpt;

require '../vendor/autoload.php';
require '../config.php';

global $pmc;
$pmc = new Memcache();
$pmc->addServer('127.0.0.1', 11209);
$response = Gpt::generate('Привет как дела?', 1, 'tg', false, 'gpt-4o');

echo $response;
