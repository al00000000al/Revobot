<?php

namespace Revobot\Commands;

use Revobot\Revobot;

class IdCmd extends BaseCmd
{
    private Revobot $bot;

    const KEYS = ['id','ид'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'Мой ид';

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
    }

    public function exec(): string
    {
        return (string)$this->bot->getUserId();
    }

}
