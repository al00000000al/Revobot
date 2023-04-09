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

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
        $this->setDescription("Введите /ai текст");
    }

    public function exec(): string
    {
        if (!empty($this->input)) {
            $context = (string)$this->getContext();
if(!empty($context)) {
    return OpenAIService::generate((string)$this->input, $context);
}else{
    return OpenAIService::generate((string)$this->input);
}
        }
        return $this->description;
    }


    private function getContext(){
        $result = $this->bot->pmc->get($this->getKey());
if(!$result) {
    return "";
}
return $result;
    }

    private function getKey(){
        return self::PMC_USER_AI_KEY . $this->bot->provider . $this->bot->getUserId();
}
}
