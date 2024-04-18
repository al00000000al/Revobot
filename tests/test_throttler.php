<?php

use Revobot\Util\PMC;

require_once  __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../config.php';

const PMC_THROTTLE_KEY = 'throttle_';
const PMC_BLOCK_KEY = 'block_';

$user_id = 1422537077;
$section = 'showcmd';

$key = getKey($user_id, $section);
$blockKey = getBlockKey($user_id, $section);

$isBlocked = PMC::get($blockKey);
$attempts = PMC::get($key);

function getKey($user_id, $section)
{
    return PMC_THROTTLE_KEY . $user_id . '_' . $section;
}

function getBlockKey($user_id, $section)
{
    // Метод для получения ключа блокировки
    return PMC_BLOCK_KEY . $user_id . '_' . $section;
}
