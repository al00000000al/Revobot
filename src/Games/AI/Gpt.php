<?php

namespace Revobot\Games\AI;

use Revobot\Services\OpenAIService;

class Gpt
{
    public static function generate(string $user_input, int $user_id, string $provider, bool $clear_all = false, $model = 'gpt-3.5-turbo') {
        $GptPMC = new GptPMC($user_id, $provider);

        if ($clear_all) {
            Clear::all($GptPMC);
        }

        $save_history = !$clear_all;

        return self::process($user_input, self::formatContext($GptPMC->getContext()), $GptPMC->getHistory(), $GptPMC, $save_history, $model);
    }

    private static function process($user_input, $context, $history, GptPMC $GptPMC, $save_history = true, $model = 'gpt-3.5-turbo') {
        $answer = OpenAIService::generate($user_input, $context, $history, $model);

        if(!empty($answer)) {
            if ($save_history) {
                $history = OpenAIService::addMessageToHistory($history, 'user', $user_input);
                $history = OpenAIService::addMessageToHistory($history, 'assistant', $answer);
                $GptPMC->setHistory($history);
            }
            return $answer;
        }

        return "Неудалось выполнить запрос к апи";
    }

    private static function formatContext(string $context) {
        $current_date = date('Y-m-d H:i:s');
        $date_message = ". Текущая дата: {$current_date}";

        if(empty($context)) {
            $context = "Ты полезный чат бот";
        }

        $context .= $date_message;

        return $context;
    }
}
