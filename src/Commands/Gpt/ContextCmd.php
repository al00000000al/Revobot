<?php

namespace Revobot\Commands\Gpt;

use Revobot\Commands\BaseCmd;
use Revobot\Games\AI\GptPMC;
use Revobot\Revobot;

class ContextCmd extends BaseCmd
{
    private Revobot $bot;
    public const KEYS = ['context','контекст','c', 'кнт'];
    public const IS_ENABLED = true;
    public const HELP_DESCRIPTION = 'Контекст';

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
    }

    public function exec(): string
    {
        $GptPMC = new GptPMC($this->bot->pmc, $this->bot->getUserId(), $this->bot->provider);
        if(empty($this->input)) {
            return $GptPMC->getContext();
        }
        $GptPMC->setContext($this->input);
        return "Контекст изменен";

    }

}
