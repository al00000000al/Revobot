<?php

namespace Revobot\Services;

use Revobot\Config;
use Revobot\Util\Curl;

class FCQuestions
{

    private const BASE_URL = 'https://fc-questions.trainzland.ru/api.php?key=';

    /**
     * @return mixed
     */
    public static function get()
    {
        $response = Curl::get(self::BASE_URL . Config::get('fc_key'));
        return json_decode($response, true);
    }
}
