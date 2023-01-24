<?php

namespace Revobot\Commands;

use Revobot\Commands\Custom\CustomCmd;
use Revobot\Games\Todo;
use Revobot\Revobot;

class PollCmd extends BaseCmd
{

    const KEYS = ['poll','опрос'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'Создать опрос в чате';
    private Revobot $bot;

    /**
     * @param $input
     * @param Revobot $bot
     */
    public function __construct($input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
        $this->setDescription('Введите /poll тема опроса');
    }

    /**
     * @return string
     */
    public function exec(): string
    {
        if(empty($this->input)){
            return $this->description;
        }
        if ($this->bot->provider === 'tg') {
            $this->bot->sendPollTg($this->input, ["Да", "Нет", "Не знаю"]);
            return "";
        }else{
            return "тут не работает";
        }
    }

}
