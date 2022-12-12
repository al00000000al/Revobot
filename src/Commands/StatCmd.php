<?php

namespace Revobot\Commands;

use Revobot\Money\Stat;
use Revobot\Revobot;

class StatCmd extends BaseCmd
{
    private Revobot $bot;

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
    }

    public function exec(): string
    {
        return (new Stat($this->bot))->get();
    }
}
