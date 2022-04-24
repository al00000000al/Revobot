<?php

namespace Revobot\Commands;

use Revobot\Revobot;
use Revobot\Services\TlgrmApp;
use Revobot\Util\Strings;

class ChatCmd extends BaseCmd
{
    private Revobot $bot;

    private const CHARACTERS = 'abcdefghijklmnopqrstuvwxyzйцукенгшщзхъфывапролджэячсмитьбю';

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
    }

    public function exec(): string
    {
       if($this->bot->provider === 'tg'){
           return TlgrmApp::search(Strings::random(mt_rand(4,5), self::CHARACTERS));
       }
       return '';
    }
}