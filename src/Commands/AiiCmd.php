<?php

namespace Revobot\Commands;
use Revobot\Revobot;

class AiiCmd extends BaseCmd
{
    private Revobot $bot;
    public const KEYS = ['aii', 'иии'];
    public const IS_ENABLED = true;
    public const HELP_DESCRIPTION = 'очистить историю и контекст';
    private const PMC_USER_AI_HISTORY_KEY = 'pmc_user_ai_history_';
    private const PMC_USER_AI_KEY = 'pmc_user_ai_';

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
    }

    public function exec(): string
    {
        $this->deleteHistory();
        $this->setContext("");
        return 'История и контекст сброшены!';
    }

    private function deleteHistory()
    {
        $this->bot->pmc->delete($this->getHistoryKey());
    }

    private function setContext(string $context){
        $this->bot->pmc->set($this->getContextKey(), $context);
    }

    private function getHistoryKey()
    {
        return self::PMC_USER_AI_HISTORY_KEY . $this->bot->provider . $this->bot->getUserId();
    }

    private function getContextKey(){
        return self::PMC_USER_AI_KEY . $this->bot->provider . $this->bot->getUserId();
    }
}
