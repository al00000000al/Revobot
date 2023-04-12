<?php

namespace Revobot\Services;

use Orhanerday\OpenAi\OpenAi;

class OpenAIService
{


    public static function generate(string $input, string $context, array $history, $options = []): string
    {

        $open_ai = new OpenAi(OPENAI_API_KEY);

        $messages  = [];
        self::addMessageToHistory($messages, 'system', $context);
        if(!empty($history)) {
            $messages += $history;
        }
        self::addMessageToHistory($messages, 'user', $input);

        $chat = $open_ai->chat([
           'model' => $options['model'] ?? 'gpt-3.5-turbo',
           'messages' => $messages,
           'temperature' => $options['temperature'] ?? 1.0,
           'max_tokens' => $options['max_tokens'] ?? 300,
           'frequency_penalty' => 0,
           'presence_penalty' => 0,
        ]);

        $d = (array)json_decode($chat, true);
        // Get Content
        return (string)$d['choices'][0]['message']['content'];
    }

    public static function addMessageToHistory(&$history, string $role, string $content) {
        $history[] = array('role' => $role, 'content' => $content);

        // если количество сообщений больше 9, удаляем первые два сообщения
        if (count($history) > 9) {
          array_splice($history, 0, 2);
        }
      }
}
