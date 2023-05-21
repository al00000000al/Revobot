<?php

namespace Revobot\Commands\Gpt;

use Revobot\Commands\BaseCmd;
use Revobot\Games\AI\GptPMC;
use Revobot\Revobot;

class ClearContextCmd extends BaseCmd
{
    private Revobot $bot;
    public const KEYS = ['clearcontext','ксброс','cc'];
    public const IS_ENABLED = true;
    public const HELP_DESCRIPTION = 'Установить контекст';

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
    }

    public function exec(): string
    {
        $GptPMC = new GptPMC($this->bot->pmc, $this->bot->getUserId(), $this->bot->provider);
        $GptPMC->setContext("");
        return "Контекст удален";
    }

}
