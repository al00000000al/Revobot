<?php

namespace Revobot\Commands;

use Revobot\Revobot;

class ResetCmd extends BaseCmd
{
    private Revobot $bot;

    public const KEYS = ['reset','clear', 'flush','сброс'];
    public const IS_ENABLED = true;
    public const HELP_DESCRIPTION = 'Сбросить историю';

    private const PMC_USER_AI_HISTORY_KEY = 'pmc_user_ai_history_';

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
        $this->setDescription("Введите /reset для сброса истории");
    }

    public function exec(): string
    {
        $this->deleteHistory();
        return "История сообщений очищена";
    }


    private function deleteHistory(){
        $this->bot->pmc->delete($this->getKey());
    }

    private function getKey(){
        return self::PMC_USER_AI_HISTORY_KEY . $this->bot->provider . $this->bot->getUserId();
}
}
