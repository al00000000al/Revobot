<?php

namespace Revobot\Services;

use Revobot\Config;

class OpenAIService
{


    public static function generate(string $input, string $context, array $history, string $model = 'gpt-3.5-turbo', $temperature = 0.8, $max_tokens = 1000)
    {
        $messages = [];
        $messages = self::addMessageToHistory($messages, 'system', $context);

        #ifndef KPHP
        // Какая-то фигня, не компилируется - Can not compile foreach on array of Unknown type
        for ($i = 0; $i < count($history); $i++) {
            if (!is_array($history[$i])) {
                continue; // Убедитесь, что текущий элемент является массивом
            }

            $message = $history[$i];
            $role = isset($message['role']) ? (string)$message['role'] : 'user';
            $content = isset($message['content']) ? (string)$message['content'] : 'hi';
            $messages = self::addMessageToHistory($messages, $role, $content);
        }
        #endif

        $messages = self::addMessageToHistory($messages, 'user', $input);


        $ch = curl_init(Config::get('openai_api_host'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query(['q' => json_encode([
            'model' => $model,
            'messages' => $messages,
            'temperature' => $temperature,
            'max_tokens' => $max_tokens,
            // 'frequency_penalty' => 0.5,
            // 'presence_penalty' => 0.6,
        ]), 'key' => Config::get('openai_api_key')]));
        $chat = curl_exec($ch);

        $d = (array)json_decode($chat, true);

        print_r($d);

        return [(string)$d['choices'][0]['finish_reason'], (string)$d['choices'][0]['message']['content']];
    }

    /**
     * @param array $history
     * @param string $role
     * @param string $content
     * @return array
     */
    public static function addMessageToHistory(array $history, string $role, string $content)
    {
        $history[] = ['role' => $role, 'content' => $content];

        // если количество сообщений больше 9, удаляем первые два сообщения
        if (count($history) > 9) {
            array_splice($history, 0, 2);
        }
        return $history;
    }
}
