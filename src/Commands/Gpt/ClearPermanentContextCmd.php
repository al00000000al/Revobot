<?php

namespace Revobot\Commands\Gpt;

use Revobot\Commands\BaseCmd;
use Revobot\Games\AI\GptPMC;
use Revobot\Revobot;

class ClearPermanentContextCmd extends BaseCmd
{
    private Revobot $bot;
    public const KEYS = ['clearpermanencontext'];
    public const IS_ENABLED = true;
    public const HELP_DESCRIPTION = 'Очистить перманентный контекст';

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
    }

    public function exec(): string
    {
        $GptPMC = new GptPMC(userId(), $this->bot->provider);
        $GptPMC->setContextPermanent("");
        return "Контекст удален";
    }
}
