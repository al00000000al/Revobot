<?php

namespace Revobot\Commands;

use Revobot\Games\AI\Clear;
use Revobot\Games\AI\GptPMC;
use Revobot\Revobot;

class ClearAllCmd extends BaseCmd
{
    private Revobot $bot;
    public const KEYS = ['clearall', 'очиститьвсе'];
    public const IS_ENABLED = true;
    public const HELP_DESCRIPTION = 'очистить историю и контекст';

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
    }

    public function exec(): string
    {
        Clear::all(new GptPMC($this->bot->pmc, $this->bot->getUserId(), $this->bot->provider));

        return 'История и контекст сброшены!';
    }

}
