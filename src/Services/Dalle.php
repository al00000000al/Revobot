<?php

namespace Revobot\Services;

use Revobot\Config;
use Revobot\Util\Curl;

class Dalle {

    const API_URL = 'https://api.openai.com/v1/images/generations';
    const MODEL   = 'dall-e-3';
    const SIZE    = '512x512';

    public static function generate($input) {
        $data = Curl::post(self::API_URL,
        json_encode([
            'prompt' => $input,
            'n' => 1,
            'size' => self::SIZE,
            'model' => self::MODEL
        ]),
        ['headers' => ['Authorization: Bearer '.Config::get('openai_api_key'), 'Content-Type: application/json']]);

        if(isset($data['error']['message'])) {
            return tuple(-1, (string)$data['error']['message']);
        }

        if(isset($data['data']) && isset($data['data'][0]['url'])) {
            $photo = (string)$data['data'][0]['url'];
            return tuple(0, $photo);
        }
    }
}
