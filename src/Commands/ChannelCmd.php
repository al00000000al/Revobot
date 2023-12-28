<?php

namespace Revobot\Commands;

use Revobot\Revobot;
use Revobot\Services\TlgrmApp;
use Revobot\Util\Strings;

class ChannelCmd extends BaseCmd
{
    private Revobot $bot;

    const KEYS = ['channel', 'канал'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'Случайный канал';

    private const CHARACTERS = 'abcdefghijklmnopqrstuvwxyz0123456789йцукенгшщзфывапролдячсмить';

    /**
     * @param string $input
     * @param Revobot $bot
     */
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
        $this->bot->sendTypeStatusTg();
        $tries = 0;
        while ($tries < 5) {
            $query = Strings::random(mt_rand(1, 2), self::CHARACTERS);
            $res = TlgrmApp::search($query);
            if (empty($res)) {
                $tries++;
            } else {
                return $res;
            }
        }
        return '';
    }
}
