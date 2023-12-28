<?php

namespace Revobot\Games\AI;

use Revobot\Services\OpenAIService;

class Gpt
{
    const PMC_USER_AI_INPUT_KEY = 'pmc_user_ai_input_';
    public static function generate(string $user_input, int $user_id, string $provider, bool $clear_all = false, $model = 'gpt-3.5-turbo')
    {
        $GptPMC = new GptPMC($user_id, $provider);


        if ($clear_all) {
            Clear::all($GptPMC);
        }

        // self::setInput($GptPMC, $user_id);

        $save_history = true;

        return self::process($user_input, self::formatContext($GptPMC->getContext()), $GptPMC->getHistory(), $GptPMC, $save_history, $model);
    }

    private static function process($user_input, $context, $history, GptPMC $GptPMC, $save_history = true, $model = 'gpt-3.5-turbo')
    {
        list($_, $answer) = OpenAIService::generate($user_input, $context, (array)$history, $model);

        if ($save_history) {
            $history = OpenAIService::addMessageToHistory($history, 'user', (string)$user_input);
            $history = OpenAIService::addMessageToHistory($history, 'assistant', (string)$answer);
            $GptPMC->setHistory($history);
        }
        return $answer;
    }

    private static function formatContext(string $context)
    {
        $current_date = date('Y-m-d H:i:s');
        $date_message = ". Текущая дата: {$current_date}";

        if (empty($context)) {
            $context = "Ты полезный чат бот";
        }

        $context .= $date_message;

        return $context;
    }

    static function setInput($GptPMC, $user_id)
    {
        $GptPMC->set(self::getInputKey($user_id));
    }

    static function getInputKey($user_id)
    {
        return PMC_USER_AI_INPUT_KEY . 'tg' . $user_id;
    }
}
