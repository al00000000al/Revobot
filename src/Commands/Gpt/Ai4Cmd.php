<?php

namespace Revobot\Commands\Gpt;

use Revobot\Commands\BaseCmd;
use Revobot\Games\AI\Gpt;
use Revobot\Money\Revocoin;
use Revobot\Revobot;

class Ai4Cmd extends BaseCmd
{
    private Revobot $bot;
    public const KEYS = ['aich','иич'];
    public const IS_ENABLED = true;
    public const HELP_DESCRIPTION = 'gpt-4 (50R)';
    public const PRICE = 50;

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
        $this->setDescription("Введите /ai4 текст (50R)");
    }

    public function exec(): string
    {
        if (!empty($this->input)) {
            if($this->hasMoney($this->bot->getUserId())) {
                (new Revocoin($this->bot))->transaction(self::PRICE, $this->bot->getTgBotId(), $this->bot->getUserId());
                return '-' . self::PRICE . " R\n"
                . Gpt::generate($this->input, $this->bot->pmc, $this->bot->getUserId(), $this->bot->provider, false, 'gpt-4');
            } else {
                return "Недостаточно ревокоинов на балансе";
            }
        }
        return $this->description;
    }

    public function hasMoney(int $user_id): bool
    {
        $revocoin = new Revocoin($this->bot);
        $user_balance = $revocoin->getBalance($user_id);
        return (int)$user_balance >= self::PRICE;
    }
}
