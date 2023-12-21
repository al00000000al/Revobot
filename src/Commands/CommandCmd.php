<?php

namespace Revobot\Commands;

use Revobot\Commands\Custom\CustomCmd;
use Revobot\Commands\Custom\Prices;
use Revobot\Commands\Custom\Types;
use Revobot\Money\Revocoin;
use Revobot\Revobot;
use Revobot\Util\Strings;

class CommandCmd extends BaseCmd
{
    private Revobot $bot;

    const KEYS = ['cmd', 'кмд', 'команда', 'command', 'комманда'];
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

        if (empty($this->input)) {
            return $this->description;
        }
        $customCmd = new CustomCmd($this->bot);

        list($command_name, $text) = Strings::parseSubCommand($this->input);

        if (!$customCmd->isValidCommand($command_name)) {
            return 'Недопустимое имя';
        }

        if (!$customCmd->hasMoney(Types::TYPE_TEXT) || empty($command_name)) {
            return 'Недостаточно ревокоинов.';
        }

        if ($customCmd->isExistCmd($command_name) || $customCmd->isExistCustomCmd($command_name)) {
            return 'Такая команда уже есть.';
        }

        $user_id = $this->bot->getUserId();

        $customCmd->addCommand($user_id, $command_name, Types::TYPE_TEXT, [$text]);
        (new Revocoin($this->bot))->transaction(Prices::PRICE_TEXT, $this->bot->getTgBotId(), $user_id);
        return 'Команда /' . $command_name . ' создана! ' . "\n" . '-' . Prices::PRICE_TEXT . 'R';
    }
}
