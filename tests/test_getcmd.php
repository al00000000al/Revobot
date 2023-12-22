<?php

use Revobot\Util\PMC;

set_time_limit(0);
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../vendor/autoload.php';

$cmd = PMC::get('custom_cmd_#');
print_r($cmd);
