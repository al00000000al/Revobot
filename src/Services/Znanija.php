<?php

namespace Revobot\Services;

use Revobot\Util\Curl;

class Znanija
{
    const BASE_URL = 'https://srv-unified-search.external.search-systems-production.z-dn.net/api/v1/ru/search';

    const RESULTS_COUNT = 10;

    public static function get(string $input): string
    {
        $response = Curl::post(self::BASE_URL, json_encode(
            [
                'query' => ['text' => $input],
                'context' => ['supportedTypes' => ['question']],
                'pagination' => ['cursor' => null, 'limit' => self::RESULTS_COUNT],
            ]
        ));
        return self::_getAnswer((array)json_decode($response, true));
    }

    private static function _getAnswer(array $response): string
    {
        if (isset($response['results'])) {
            $cnt = count($response['results']);
            return self::_format((string)$response['results'][mt_rand(0, $cnt - 1)]['question']['answer']['content']);
        }
        return '';
    }

    private static function _format(string $answer)
    {
        $answer = strtr($answer, ['<p>' => '', '</p>' => "\r\n"]);
        $breaks = array("<br />", "<br>", "<br/>");
        $answer = str_replace($breaks, "\r\n", $answer);
        $answer = strip_tags($answer);
        return $answer;
    }
}
