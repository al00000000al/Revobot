<?php

use Revobot\Util\PMC;

set_time_limit(0);
require_once  __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config.php';

while (true) {
    $key = readline('Enter the key to retrieve data: ');

    if (empty($key)) {
        exit;
    }

    $data = PMC::get($key);
    if (is_array($data)) {
        $xdata = [];
        foreach ($data as $f) {
            if (is_array($f)) {
                $xdata[] = $f;
            } else {
                try {
                    $xdata[] = json_decode($f, true);
                } catch (Exception $_) {
                    $xdata[] = $f;
                }
            }
        }
    } else {
        try {
            $xdata = json_decode($data, true);
        } catch (Exception $_) {
            $xdata = $data;
        }
    }

    if ($data === null) {
        echo "No data found for the key: $key\n";
    } else {
        print_r($xdata);
    }
}
