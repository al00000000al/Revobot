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

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->setDescription('/command команда текст');
        $this->bot = $bot;
    }

    public function exec(): string
    {
        $customCmd = new CustomCmd($this->bot);
        $command_name = (string)explode(' ', $this->input)[0];

        if (!$customCmd->isValidCommand($command_name)) {
            return 'Недопустимое имя';
        }

        if(!$customCmd->hasMoney(Types::TYPE_TEXT) || empty($command_name)){
            return 'Недостаточно ревокоинов.';
        }

        if($customCmd->isExistCmd($command_name) || $customCmd->isExistCustomCmd($command_name)){
            return 'Такая команда уже есть.';
        }

        $customCmd->addCommand($this->bot->getUserId(), $command_name, Types::TYPE_TEXT, []);
        (new Revocoin($this->bot))->transaction(Prices::PRICE_TEXT, -TG_BOT_ID, $this->bot->getUserId());
        return 'Команда /'.$command_name.' создана!';
    }
}