<?php

namespace Revobot\Services;

use Revobot\Util\Curl;

class DobroAI
{

    private const BASE_URL = 'https://pelevin.gpt.dobro.ai/generate/';

    public static function get($start): string
    {
        $response = Curl::post(
            self::BASE_URL,
            json_encode(["prompt" => $start, "length" => 50, "num_samples" => 1]),
            ['headers' => ['Content-Type: application/json']]
        );
        if(!$response){
            return '';
        }

        if (isset($response['replies'][0])) {
            return (string)$response['replies'][0];
        }
        return '';
    }
}
