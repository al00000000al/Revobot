<?php
$file = explode("\n", file_get_contents('/binlogdump.txt'));

$pmc = new Memcache;
$pmc->addServer('127.0.0.1', 11209);

foreach ($file as $line) {
    $item = explode("	", $line);
    if ($item[0] === 'LEV_PMEMCACHED_STORE_FOREVER+1') {
        if ((substr($item[1], 0, 11) !== 'custom_cmd_') &&  (substr($item[1], 0, 11) !== 'custom_user')) {
            $pmc->set($item[1], $item[2]);
        }

    } else if ($item[0] === 'LEV_PMEMCACHED_STORE+1') {
        if ((substr($item[3], 0, 11) !== 'custom_cmd_') &&  (substr($item[3], 0, 11) !== 'custom_user')) {

            $pmc->set($item[3], $item[4]);
        }
    } else if ($item[0] === 'LEV_PMEMCACHED_DELETE') {
        $pmc->delete($item[1]);
    }

}
