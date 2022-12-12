<?php

namespace Revobot\Services;

use Revobot\Util\Curl;

class OpenAI
{
    private const BASE_URL = 'http://127.0.0.1:5000/api/chat';

    /**
     * @param string $input
     * @return array
     */
    public static function generate(string $input, string $conversation_id): string
    {
        $result = Curl::post(self::BASE_URL, ['text' => $input, 'conversation_id' => $conversation_id]);

        return $result ?? '';
    }
}
