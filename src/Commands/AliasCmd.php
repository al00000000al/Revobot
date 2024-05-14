<?php

namespace Revobot\Commands;

use Revobot\Commands\Custom\CustomCmd;
use Revobot\Commands\Custom\Prices;
use Revobot\Commands\Custom\Types;
use Revobot\Config;
use Revobot\Money\Revocoin;
use Revobot\Revobot;
use Revobot\Util\Strings;

class AliasCmd extends BaseCmd
{
    private Revobot $bot;

    const KEYS = ['alias', 'алиас'];
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
        if (empty($this->input)) {
            return $this->description;
        }
        list($command_name, $command) = Strings::parseTwoCommands($this->input);
        $customCmd = new CustomCmd($this->bot);

        if ($command === 'execute') {
            return 'Нельзя создавать алиасы к execute';
        }

        if (!$customCmd->isValidCommand($command_name) || empty($command) || empty($command_name)) {
            return 'Недопустимое имя';
        }

        if (!$customCmd->hasMoney(Types::TYPE_ALIAS) || empty($command_name)) {
            return 'Недостаточно ревокоинов.';
        }

        if ($customCmd->isExistCmd($command_name) || $customCmd->isExistCustomCmd($command_name)) {
            return 'Такая команда уже есть.';
        }

        $user_id = userId();

        $customCmd->addCommand($user_id, $command_name, Types::TYPE_ALIAS, [$command]);
        (new Revocoin($this->bot))->transaction(Prices::PRICE_ALIAS, $this->bot->getBotId(), $user_id);
        return 'Команда /' . $command_name . ' создана! ' . "\n" . '-' . Prices::PRICE_ALIAS . 'R';
    }
}
