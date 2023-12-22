<?php

namespace Revobot\Services;

use Revobot\Config;
use Revobot\Util\Curl;

class Dalle
{

    const API_URL = 'https://api.openai.com/v1/images/generations';
    const MODEL   = 'dall-e-3';
    const SIZE    = '1024x1024';

    public static function generate($input)
    {
        $data = Curl::post(
            Config::get('openai_api_host'),
            http_build_query([
                'q' => json_encode([
                    'prompt' => $input,
                    'n' => 1,
                    'size' => self::SIZE,
                    'model' => self::MODEL
                ]),
                'key' => Config::get('openai_api_key'),
                'dalle' => 1,
            ]),
            ['need_json_decode' => true]
        );

        if (isset($data['error']['message'])) {
            return [-1, (string)$data['error']['message']];
        }

        if (isset($data['data']) && isset($data['data'][0]['url'])) {
            $photo = (string)$data['data'][0]['url'];
            return [0, $photo];
        }

        return [-1, "Unknown error"];
    }
}
