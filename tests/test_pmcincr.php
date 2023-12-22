<?php

$pmc = new Memcache;
$pmc->addServer('127.0.0.1', 11209);
$key = 'pmc_huekey1';

if ($pmc->increment($key, 1) === false) {
    $pmc->set($key, 1);
}
print_r($pmc->get($key));
$pmc->increment($key, 1);
print_r($pmc->get($key));
$pmc->increment($key, 1);
print_r($pmc->get($key));
