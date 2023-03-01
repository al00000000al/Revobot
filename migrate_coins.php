<?php
set_time_limit(0);
require_once __DIR__ . '/config.php';

$pmc = new Memcache;
$pmc->addServer('127.0.0.1', 11209);

$from = 'money_tg5381901763';
$to = 'money_tg-5381901763';

$from_coins = (int)$pmc->get($from);
$to_coins = (int)$pmc->get($to);

$result = $to_coins + $from_coins;

echo "From: {$from} = {$from_coins}\r\n";
echo "To: {$to} = {$to_coins}\r\n";
echo "Result: {$to} = {$result}\r\n";

$pmc->set($to, $result);
$pmc->set($from, 0);
echo "OK!\r\n";
