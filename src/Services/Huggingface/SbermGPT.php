<?php

namespace Revobot\Services\Huggingface;

use Revobot\Config;
use Revobot\Util\Curl;

class SbermGPT
{
    private const BASE_URL = 'https://api-inference.huggingface.co/models/sberbank-ai/mGPT';

    public static function get(string $input): string
    {

        $data = [
            'inputs' => $input
        ];

        $res = Curl::post(self::BASE_URL, (string)json_encode($data), ['headers' => [
            'Authorization: Bearer ' . Config::get('huggingface_key')
        ]]);
        if(isset($res[0]['generated_text'])){
            return (string)$res[0]['generated_text'];
        }
        return '';
    }
}
