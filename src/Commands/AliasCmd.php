<?php

namespace Revobot\Commands;

use Revobot\Commands\Custom\CustomCmd;
use Revobot\Commands\Custom\Prices;
use Revobot\Commands\Custom\Types;
use Revobot\Money\Revocoin;
use Revobot\Revobot;

class AliasCmd extends BaseCmd
{
    private Revobot $bot;

    const KEYS = ['alias','алиас'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'Создать алиас (10R)';

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->setDescription('/alias новое_название команда');
        $this->bot = $bot;
    }

    public function exec(): string
    {
        if (empty($this->input)){
            return $this->description;
        }
        $text_arr = explode(' ', $this->input);
        $command_name = (string)array_shift($text_arr);

        $customCmd = new CustomCmd($this->bot);
        $command = (string)$text_arr[0];

        if (!$customCmd->isValidCommand($command_name)) {
            return 'Недопустимое имя';
        }

        if(!$customCmd->hasMoney(Types::TYPE_ALIAS) || empty($command_name)){
            return 'Недостаточно ревокоинов.';
        }

        if($customCmd->isExistCmd($command_name) || $customCmd->isExistCustomCmd($command_name)){
            return 'Такая команда уже есть.';
        }

        $customCmd->addCommand($this->bot->getUserId(), $command_name, Types::TYPE_ALIAS, [$command]);
        (new Revocoin($this->bot))->transaction(Prices::PRICE_ALIAS, -TG_BOT_ID, $this->bot->getUserId());
        return 'Команда /'.$command_name.' создана! '."\n".'-'.Prices::PRICE_ALIAS.'R';
    }
}
