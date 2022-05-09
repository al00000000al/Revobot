<?php

namespace Revobot\Commands;

use Revobot\Revobot;

class IdCmd extends BaseCmd
{
    private Revobot $bot;

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
    }

    public function exec(): string
    {
        return $this->bot->getUserId();
    }

}