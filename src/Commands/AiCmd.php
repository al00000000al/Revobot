<?php

namespace Revobot\Commands;

use Revobot\Neural\Answers;
use Revobot\Revobot;
use Revobot\Services\OpenAIService;

class AiCmd extends BaseCmd
{
    private Revobot $bot;

    public const KEYS = ['ai','ии'];
    public const IS_ENABLED = true;
    public const HELP_DESCRIPTION = 'Нейросеть';

    private const PMC_USER_AI_KEY = 'pmc_user_ai_';
    private const PMC_USER_AI_HISTORY_KEY = 'pmc_user_ai_history_';

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
        $this->setDescription("Введите /ai текст");
    }

    public function exec(): string
    {
        if (!empty($this->input)) {
            $user_input = (string)$this->input;
            $context = $this->getContext();
            $history = $this->getHistory();
            $current_date = date('Y-m-d H:i:s');
            $date_message = ". Текущая дата: {$current_date}";

            if(empty($context)){
                $context = "Ты полезный чат бот";
            }

            $context .= $date_message;
            $answer = OpenAIService::generate($user_input, $context, $history);

            if(!empty($answer)){
                OpenAIService::addMessageToHistory($history, 'user', $user_input);
                OpenAIService::addMessageToHistory($history, 'assistant', $answer);
                self::setHistory($history);
                return $answer;
            }
        }
        return $this->description;
    }


    private function getContext(){
        $result = (string) $this->bot->pmc->get($this->getContextKey());
        return $result;
    }

    private function getHistory(){
        $result = (array) json_decode($this->bot->pmc->get($this->getHistoryKey()), true);
        return $result;
    }

    private function setHistory(array $history){
        $json_encoded = json_encode($history);
        $this->bot->pmc->set($this->getHistoryKey(), $json_encoded);
    }

    private function getContextKey(){
        return self::PMC_USER_AI_KEY . $this->bot->provider . $this->bot->getUserId();
    }

    private function getHistoryKey(){
        return self::PMC_USER_AI_HISTORY_KEY . $this->bot->provider . $this->bot->getUserId();
    }
}
