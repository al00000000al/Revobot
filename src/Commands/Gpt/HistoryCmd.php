<?php

namespace Revobot\Commands\Gpt;

use Revobot\Commands\BaseCmd;
use Revobot\Games\AI\GptPMC;
use Revobot\Revobot;

class HistoryCmd extends BaseCmd
{
    private Revobot $bot;
    public const KEYS = ['history','h', 'ист', 'история'];
    public const IS_ENABLED = true;
    public const HELP_DESCRIPTION = 'История';

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
    }

    public function exec(): string
    {
        $GptPMC = new GptPMC($this->bot->pmc, $this->bot->getUserId(), $this->bot->provider);

        $history = $GptPMC->getHistory();

        $result = '';
        foreach ($history as $item) {
            $result .= '- '.$item['role'] .': '.$item['content']."\n";
        }
        return $result;
    }

}
