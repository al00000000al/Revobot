<?php

namespace Revobot\Commands;

use Revobot\Games\AI\Gpt;
use Revobot\Money\Revocoin;
use Revobot\Revobot;

class Ai4Cmd extends BaseCmd
{
    private Revobot $bot;
    public const KEYS = ['ai4','ии4'];
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
                (new Revocoin($this->bot))->transaction(self::PRICE, -TG_BOT_ID, $this->bot->getUserId());
                return Gpt::generate($this->input, $this->bot->pmc, $this->bot->getUserId(), $this->bot->provider, true, 'gpt-4');
            } else {
                return "Недостаточно ревокоинов на балансе";
            }
        }
        return $this->description;
    }

    public function hasMoney(int $user_id): bool
    {
        $user_balance = (new Revocoin($this->bot))->getBalance($user_id);
        return $user_balance >= self::PRICE;
    }
}
