<?php

namespace Revobot\Commands;

use Revobot\Commands\Custom\CustomCmd;
use Revobot\Commands\Custom\Prices;
use Revobot\Commands\Custom\Types;
use Revobot\Money\Revocoin;
use Revobot\Revobot;

class NewcodeCmd extends BaseCmd
{
    private Revobot $bot;
    const KEYS = ['newcode', 'новыйкод'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'Создать команду с кодом lua (30R)';

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->setDescription('/newcode команда код');
        $this->bot = $bot;
    }

    public function exec(): string
    {

        if (empty($this->input)) {
            return $this->description;
        }
        $customCmd = new CustomCmd($this->bot);
        $parts = explode(' ', $this->input);
        $command_name = $parts[0];
        $text = implode(' ', array_slice($parts, 1));


        if (!$customCmd->isValidCommand($command_name)) {
            return 'Недопустимое имя';
        }

        if (!$customCmd->hasMoney(Types::TYPE_CODE) || empty($command_name)) {
            return 'Недостаточно ревокоинов.';
        }

        if ($customCmd->isExistCmd($command_name) || $customCmd->isExistCustomCmd($command_name)) {
            return 'Такая команда уже есть.';
        }

        $user_id = $this->bot->getUserId();

        $customCmd->addCommand($user_id, $command_name, Types::TYPE_CODE, [json_encode(['code' => $text])]);
        (new Revocoin($this->bot))->transaction(Prices::PRICE_CODE, $this->bot->getTgBotId(), $user_id);
        return 'Команда /' . $command_name . ' создана! ' . "\n" . '-' . Prices::PRICE_CODE . 'R';
    }
}
