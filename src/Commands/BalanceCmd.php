<?php

namespace Revobot\Commands;

use Revobot\Money\Revocoin;
use Revobot\Revobot;

class BalanceCmd extends BaseCmd
{
    private Revobot $bot;
    const KEYS = ['balance','баланс',];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'Узнать свой баланс';
    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
    }

    public function exec(): string
    {
        return 'Баланс: '.(new Revocoin($this->bot))->getBalance($this->bot->getUserId()).' R';
    }
}
