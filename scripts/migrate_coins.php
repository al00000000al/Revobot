<?php

use Revobot\Util\PMC;

set_time_limit(0);
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../vendor/autoload.php';


$from = 'money_tg5381901763';
$to = 'money_tg-5381901763';

$from_coins = (int)PMC::get($from);
$to_coins = (int)PMC::get($to);

$result = $to_coins + $from_coins;

echo "From: {$from} = {$from_coins}\r\n";
echo "To: {$to} = {$to_coins}\r\n";
echo "Result: {$to} = {$result}\r\n";

PMC::set($to, $result);
PMC::set($from, 0);
echo "OK!\r\n";
