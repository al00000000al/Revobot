<?php

namespace Revobot\Commands;

use Revobot\Revobot;
use Revobot\Util\PMC;

class BanCmd extends BaseCmd
{
    const KEYS = ['ban'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'ban user';
    const IS_ADMIN_ONLY = true;
    public const PMC_KEY = 'fk_';
    private Revobot $bot;

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
    }

    public function exec(): string
    {
        if (!isAdmin(userid())) {
            return '';
        }

        if (provider() === 'tg') {
            $user_id = $this->bot->raw_data['reply_to_message']['from']['id'];
        }

        PMC::set(self::PMC_KEY . provider() . $user_id, 1, 0, 14400);
        return 'ok';
    }
}
