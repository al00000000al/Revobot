<?php
// kphp -F --composer-root=$(pwd) --composer-no-dev index.php
//  ./build/server -H 8088 --use-utf8 --workers-num 5  -q  &

use Revobot\Config;

require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../vendor/autoload.php';

print_r($config);


echo Config::get('tg_key');
