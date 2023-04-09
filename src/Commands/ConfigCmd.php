<?php

namespace Revobot\Commands;

use Revobot\Revobot;

class ConfigCmd extends BaseCmd
{
    private Revobot $bot;

    const KEYS = ['config','конфиг'];
    const IS_ENABLED = true;
    const IS_ADMIN_ONLY = true;
    const HELP_DESCRIPTION = 'Конфиг';

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
        $this->setDescription('Введите /config <ключ>');
    }

    public function exec(): string
    {
        if(!$this->isAdmin($this->bot->getUserId())){
            return '';
        }

        if(!empty($this->input)) {
            return print_r($this->getKey($this->input), true);
        }
        return $this->description;
    }

    private function getKey($key){
        return $this->bot->pmc->get($key);
    }


}
