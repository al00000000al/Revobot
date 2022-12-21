<?php

namespace Revobot\Commands\Key;

use Revobot\Commands\BaseCmd;
use Revobot\Revobot;

class KeyCmd extends BaseCmd
{
    private Revobot $bot;
    const KEYS = ['key','ключ'];
    const IS_ENABLED = true;
    const IS_ADMIN_ONLY = true;
    const HELP_DESCRIPTION = 'key';

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
        self::setDescription('Введите /key <ключ>');
    }

    public function exec(): string
    {
        if(!self::isAdmin($this->bot->getUserId())){
            return '';
        }

        if(!empty($this->input)) {
            return (string) print_r(self::getKey($this->input), true);
        }
        return $this->description;
    }

    private function getKey($key){
        return $this->bot->pmc->get($key);
    }


}
