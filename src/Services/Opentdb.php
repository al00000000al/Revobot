<?php

namespace Revobot\Services;

use Revobot\Util\Curl;


/**
 * questions API
 */
class Opentdb
{
    private const BASE_URL = 'https://opentdb.com/api.php';

    /**
     * @return array
     */
    public static function get(): array
    {
        $result = Curl::get(self::BASE_URL . http_build_query([
                'amount' => 10,
                'type' => 'multiple',
            ]));
        $result = json_decode($result, true);
        if (isset($result['results'])) {
            return $result['results'];
        }
        return [];
    }


}