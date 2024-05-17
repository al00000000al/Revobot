<?php

namespace Revobot\Commands;

use Revobot\Money\Revocoin;
use Revobot\Revobot;

class BankCmd extends BaseCmd
{
    const KEYS = ['bank'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'get revolucia balance';
    private Revobot $bot;

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
        $this->setDescription('/bank get revolucia balanc');
    }

    public function exec(): string
    {
        $revocoin = new Revocoin($this->bot);
        $id = $this->bot->getBotId();
        $balance1 = $revocoin->getBalance($id);
        $balance2 = $revocoin->getBalance(-$id);
        return 'Баланс ' . $id . ': ' . $balance1 . "R\n\nБаланс " . -$id . ': ' . $balance2 . 'R';
    }
}
