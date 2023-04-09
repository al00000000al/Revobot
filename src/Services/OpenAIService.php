<?php

namespace Revobot\Services;

use Revobot\Util\Curl;
use Orhanerday\OpenAi\OpenAi;

class OpenAIService
{


    public static function generate(string $input, string $context = "Тебя зовут Люся. Пиши разговорным языком не больше 15-35 слов"): string
    {

        $open_ai = new OpenAi(OPENAI_API_KEY);

        $messages  = [];
        $messages[] = [
            "role" => "system",
            "content" => $context
        ];
        $messages[] = [
            "role" => "user",
            "content" => $input
        ];

        $chat = $open_ai->chat([
           'model' => 'gpt-3.5-turbo',
           'messages' => $messages,
           'temperature' => 1.0,
           'max_tokens' => 300,
           'frequency_penalty' => 0,
           'presence_penalty' => 0,
        ]);

        $d = (array)json_decode($chat, true);
        // Get Content
        return (string)$d['choices'][0]['message']['content'];
    }
}
