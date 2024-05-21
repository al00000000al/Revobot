<?php

namespace Revobot\Commands\Gpt;

use Revobot\Commands\BaseCmd;
use Revobot\Games\AI\GptPMC;
use Revobot\Revobot;

class PermanentContextCmd extends BaseCmd
{
    private Revobot $bot;
    const KEYS = ['permanentcontext'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'установить постоянный контекст';

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
    }

    public function exec(): string
    {
        $GptPMC = new GptPMC(userId(), $this->bot->provider);
        if (empty($this->input)) {
            $response = $GptPMC->getContextPermanent();
            if (empty($response)) {
                $response = "Пустой контекст";
            }
            return $response;
        }
        $GptPMC->setContextPermanent($this->input);
        return "Контекст изменен";
    }
}
