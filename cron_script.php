<?php
set_time_limit(0);
require_once __DIR__ . '/config.php';

$pmc = new Memcache;
$pmc->addServer('127.0.0.1', 11209);

$tasks = $pmc->get("tasks#");
