<?php

namespace Revobot\Commands\Gpt;

use Revobot\Commands\BaseCmd;
use Revobot\Games\AI\GptPMC;
use Revobot\Revobot;

class ClearHistoryCmd extends BaseCmd
{
    private Revobot $bot;

    public const KEYS = ['clearhistory', 'ch', 'исброс'];
    public const IS_ENABLED = true;
    public const HELP_DESCRIPTION = 'Сбросить историю';

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
        $this->setDescription("Введите /delhistory для сброса истории");
    }

    public function exec(): string
    {
        $GptPMC = new GptPMC(userId(), $this->bot->provider);
        $GptPMC->deleteHistory();
        return "История сообщений очищена";
    }
}
