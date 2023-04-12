<?php

namespace Revobot\Services;

use Orhanerday\OpenAi\OpenAi;

class OpenAIService
{


    public static function generate(string $input, string $context, array $history, string $model = 'gpt-3.5-turbo', $temperature = 1.0, $max_tokens = 300): string
    {

        $open_ai = new OpenAi(OPENAI_API_KEY);

        $messages  = [];
        self::addMessageToHistory($messages, 'system', $context);
        foreach($history as $message){
            self::addMessageToHistory($messages, (string)$message['role'], (string)$message['content']);
        }
        self::addMessageToHistory($messages, 'user', $input);

        $chat = $open_ai->chat([
           'model' => $model,
           'messages' => $messages,
           'temperature' => $temperature,
           'max_tokens' => $max_tokens,
           'frequency_penalty' => 0,
           'presence_penalty' => 0,
        ]);

        $d = (array)json_decode($chat, true);
        // Get Content
        return (string)$d['choices'][0]['message']['content'];
    }

    public static function addMessageToHistory($history, string $role, string $content) {
        $history[] = ['role' => $role, 'content' => $content];

        // если количество сообщений больше 9, удаляем первые два сообщения
if (count($history) > 9) {
    array_splice($history, 0, 2);
}
return $history;
      }
}
