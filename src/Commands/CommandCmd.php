<?php

namespace Revobot\Commands;

use Revobot\Commands\Custom\CustomCmd;
use Revobot\Commands\Custom\Prices;
use Revobot\Commands\Custom\Types;
use Revobot\Money\Revocoin;
use Revobot\Revobot;

class CommandCmd extends BaseCmd
{
    private Revobot $bot;

    const KEYS = ['cmd','кмд','команда','command','комманда'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'Создать команду (20R)';

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->setDescription('/command команда текст');
        $this->bot = $bot;
    }

    public function exec(): string
    {

        if (empty($this->input)){
            return $this->description;
        }
        $customCmd = new CustomCmd($this->bot);


        $text_arr = explode(' ', $this->input);
        $command_name = (string)array_shift($text_arr);
        $text = implode(' ', $text_arr);


        if (!$customCmd->isValidCommand($command_name)) {
            return 'Недопустимое имя';
        }

        if(!$customCmd->hasMoney(Types::TYPE_TEXT) || empty($command_name)){
            return 'Недостаточно ревокоинов.';
        }

        if($customCmd->isExistCmd($command_name) || $customCmd->isExistCustomCmd($command_name)){
            return 'Такая команда уже есть.';
        }

        $customCmd->addCommand($this->bot->getUserId(), $command_name, Types::TYPE_TEXT, [$text]);
        (new Revocoin($this->bot))->transaction(Prices::PRICE_TEXT, -TG_BOT_ID, $this->bot->getUserId());
        return 'Команда /'.$command_name.' создана! '."\n".'-'.Prices::PRICE_TEXT.'R';
    }
}
