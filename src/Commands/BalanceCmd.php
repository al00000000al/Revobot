<?php

namespace Revobot\Commands;

use Revobot\Money\Revocoin;
use Revobot\Revobot;

class BalanceCmd extends BaseCmd
{
    private Revobot $bot;

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