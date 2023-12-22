<?php

namespace Revobot\Money;

use Revobot\Games\Predictor\Utils;
use Revobot\Revobot;
use Revobot\Util\Hash;

class Password
{

    public static function generate(int $user_id, string $service = 'tg')
    {
        return Hash::generate((string) $user_id, $service, 'password');
    }
}
