<?php

use Revobot\Util\PMC;

set_time_limit(0);
require_once  __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config.php';
$data = PMC::get('money_tg#');
print_r($data);
$data = PMC::get('money_vk#');
print_r($data);
