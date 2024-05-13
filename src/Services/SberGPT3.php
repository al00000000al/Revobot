<?php

namespace Revobot\Services;

use Revobot\Util\Curl;
use Revobot\Util\Strings;

class SberGPT3
{
    private const BASE_URL = 'https://api.aicloud.sbercloud.ru/public/v1/public_inference/gpt3/predict';

    /**
     * @param string $input
     * @return string
     */
    public static function generate(string $input): string
    {
        $input = (string)json_encode(['text' => $input]);
        $headers = [
            'Origin: https://russiannlp.github.io',
            'Referer: https://russiannlp.github.io',
            'Content-type: application/json',
            'User-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/100.0.4896.127 Safari/537.36'
        ];

        $result = Curl::post(self::BASE_URL, $input, ['headers' => $headers, 'need_json_decode' => true]);

        if (isset($result['predictions'])) {
            $output = (string)$result['predictions'];
            return Strings::substr($output, mb_strlen($input, 'UTF-8'));
        }
        return '';
    }
}
