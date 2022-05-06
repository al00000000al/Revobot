<?php

namespace Revobot\Commands;

use Revobot\Commands\Custom\CustomCmd;
use Revobot\Commands\Custom\Prices;
use Revobot\Commands\Custom\Types;
use Revobot\Money\Revocoin;
use Revobot\Revobot;

class DeleteCmd extends BaseCmd
{
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
        $user_commands = $customCmd->getUserCommands($this->bot->getUserId());
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
            }
            if ($price > 0) {
                (new Revocoin($this->bot))->transaction($price, $this->bot->getUserId(), -TG_BOT_ID);
                $customCmd->deleteCommand($this->bot->getUserId(), $this->input);
                $commission = $price * Revocoin::TRANSACTION_COMMISSION;
                $price = $price - $commission;
                return '+' . $price . 'R у ' . $this->bot->getUserNick();
            }


        }
        return 'Что-то пошло не так';

    }
}