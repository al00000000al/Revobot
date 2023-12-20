<?php

namespace Revobot\Commands;

use Revobot\Commands\Custom\CustomCmd;
use Revobot\Commands\Custom\Prices;
use Revobot\Commands\Custom\Types;
use Revobot\Money\Revocoin;
use Revobot\Revobot;

class DeleteCmd extends BaseCmd
{

    const KEYS = ['delete', 'del', 'удалить'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'Удалить команду';
    private Revobot $bot;

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
        $this->setDescription('/delete команда');
    }

    public function exec(): string
    {
        if (empty($this->input)) {
            return $this->description;
        }
        $customCmd = new CustomCmd($this->bot);
        $user_id = $this->bot->getUserId();
        $user_commands = $customCmd->getUserCommands($user_id);
        if (!in_array($this->input, $user_commands, true)) {
            return 'Вы не можете удалить эту команду или такой нет';
        }
        $cmd = $customCmd->getCustomCmd($this->input);

        $price = 0;
        if (isset($cmd['command_type'])) {
            switch ((int)$cmd['command_type']) {
                case Types::TYPE_ALIAS:
                    $price = Prices::PRICE_ALIAS;
                    break;
                case Types::TYPE_TEXT:
                    $price = Prices::PRICE_TEXT;
                    break;
                case Types::TYPE_CODE:
                    $price = Prices::PRICE_CODE;
                    break;
            }
            if ($price > 0) {
                (new Revocoin($this->bot))->transaction($price, $user_id, $this->bot->getTgBotId());
                $customCmd->deleteCommand($user_id, $this->input);
                $commission = $price * Revocoin::TRANSACTION_COMMISSION;
                $price -= $commission;
                return '+' . $price . 'R у ' . $this->bot->getUserNick();
            }
        }
        return 'Что-то пошло не так';
    }
}
