<?php

namespace Revobot\Commands;

use Revobot\Revobot;

class AliveCmd extends BaseCmd
{
    private Revobot $bot;

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
    }

    /**
     * @return string
     */
    public function exec(): string
    {
        return 'Жив! ПМС:'. $this->bot->pmc->getVersion();
    }
}
