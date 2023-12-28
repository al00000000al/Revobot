<?php

namespace Revobot\Commands\Gpt;

use Revobot\Commands\BaseCmd;
use Revobot\Games\AI\GptPMC;
use Revobot\Revobot;

class DelLastCmd extends BaseCmd
{
    private Revobot $bot;
    public const KEYS = ['dellast', 'd', 'делпослед'];
    public const IS_ENABLED = true;
    public const HELP_DESCRIPTION = 'Удалить свое последнее сообщение из истории';

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
    }

    public function exec(): string
    {
        $GptPMC = new GptPMC(userId(), $this->bot->provider);
        $history = $GptPMC->getHistory();
        array_pop($history);
        array_pop($history);
        $GptPMC->setHistory($history);
        return 'Удалено';
    }
}
