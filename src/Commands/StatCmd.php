<?php

namespace Revobot\Commands;

use Revobot\Money\Stat;
use Revobot\Revobot;

class StatCmd extends BaseCmd
{
    const KEYS = ['stat', 'стат'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'Статистика R';

    private Revobot $bot;

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
    }

    public function exec(): string
    {
        $this->bot->sendTypeStatus();
        $this->bot->sendMessage((new Stat($this->bot))->get(), 'markdown');
        return '';
    }
}
