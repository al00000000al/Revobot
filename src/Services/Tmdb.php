<?php

namespace Revobot\Services;

use Revobot\Config;
use Revobot\Util\Curl;

class Tmdb
{
    public const IMAGE_HOST = 'https://image.tmdb.org/t/p/w500/';

    public static function latest() {
        return self::_sendRequest('latest', ['language' => 'en-US']);
    }

    public static function geetById(int $id) {
       return self::_sendRequest((string)$id, ['language' => 'en-US']);
    }

    private static function _sendRequest(string $method, array $params = []):array {
        $url = Config::get('tmdb_api_host');
        $params['method'] = $method;
        $params['api_key'] = Config::get('tmdb_api_key');
        $options = ['need_json_decode' => true];
        return (array)Curl::post($url, $params, $options);
    }
}
