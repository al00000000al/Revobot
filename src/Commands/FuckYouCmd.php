<?php

namespace Revobot\Commands;

use Revobot\Revobot;
use Revobot\Util\PMC;

class FuckYouCmd extends BaseCmd
{
    const KEYS = ['fuckyou', 'идинахуй', 'пошланахуй'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'Бот не отвечает 4ч на команды';
    public const PMC_KEY = 'fk_';
    private Revobot $bot;

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
    }

    public function exec(): string
    {
        PMC::set(self::PMC_KEY . $this->bot->provider . userId(), 1, 0, 14400);
        return self::HELP_DESCRIPTION;
    }
}
