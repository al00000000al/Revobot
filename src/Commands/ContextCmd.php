<?php

namespace Revobot\Commands;

use Revobot\Revobot;

class ContextCmd extends BaseCmd
{
    private Revobot $bot;

    public const KEYS = ['context','контекст'];
    public const IS_ENABLED = true;
    public const HELP_DESCRIPTION = 'Установить контекст нейросети';

    private const PMC_USER_AI_KEY = 'pmc_user_ai_';

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
        $this->setDescription("Введите /context текст");
    }

    public function exec(): string
    {
        if (!empty($this->input)) {
            $this->setContext($this->input);
            return "Значение изменено";
        }
        $this->setContext("");
        return $this->description;
    }


    private function setContext(string $context){
        $this->bot->pmc->set($this->getKey(), $context);
    }


    private function getKey(){
        return self::PMC_USER_AI_KEY . $this->bot->provider . $this->bot->getUserId();
}
}
