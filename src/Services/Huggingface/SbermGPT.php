<?php

namespace Revobot\Services\Huggingface;

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
            'Authorization: Bearer ' . HUGGINGFACE_KEY
        ]]);
        if(isset($res[0]['generated_text'])){
            return (string)$res[0]['generated_text'];
        }
        return '';
    }
}