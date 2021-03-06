<?php

namespace Revobot\Commands;

use Revobot\Revobot;
use Revobot\Services\TlgrmApp;
use Revobot\Util\Strings;

class ChatCmd extends BaseCmd
{
    private Revobot $bot;

    private const CHARACTERS = 'abcdefghijklmnopqrstuvwxyz';

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
        if ($this->bot->provider === 'tg') {
            $tries = 0;
            while ($tries < 5) {
                $query = Strings::random(mt_rand(4, 5), self::CHARACTERS);
                $res = TlgrmApp::search($query);
                if (empty($res)) {
                    dbg_echo($query."\n");
                    $tries++;
                } else {
                    return $res;
                }
            }

        }
        return '';
    }
}
