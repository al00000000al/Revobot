<?php

namespace Revobot\Commands;

use Revobot\Revobot;
use Revobot\Money\Revocoin;

class CoinFlipCmd extends BaseCmd
{
    const KEYS = ['coinflip', 'орелирешка', 'coin', 'flip'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'Орел или решка на ревокоены';
    private Revobot $bot;

    public function __construct($input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
        $this->setDescription("/coinflip сумма орел/решка");
    }

    public function exec(): string
    {
        if (empty($this->input)) {
            return $this->description;
        }

        $parts = explode(' ', $this->input);
        if (count($parts) < 2) {
            return 'Неверный формат. Используйте /coinflip сумма орел/решка';
        }

        $amount = (float)$parts[0];
        $choice = strtolower($parts[1]);
        $user_id = userId();
        $bot_id = $this->bot->getBotId();
        $user_balance = (float)(new Revocoin($this->bot))->getBalance($user_id);

        if ($amount <= 0 || ($user_balance < $amount) || ($user_balance == 0)) {
            return 'Недостаточно средств у вас на счету';
        }

        if ($choice !== 'орел' && $choice !== 'решка') {
            return 'Выберите орел или решка';
        }

        $flip = mt_rand(0, 9) < 7 ? 'решка' : 'орел';
        if ($choice === $flip) {
            $new_amount = $amount * 1.5;
            (new Revocoin($this->bot))->transaction($new_amount, $user_id, $bot_id);
            return 'Выпал ' . $flip . '! Вы выиграли +' . ($new_amount - $amount) . ' R';
        } else {
            (new Revocoin($this->bot))->transaction($amount, $bot_id, $user_id);
            return 'Выпал ' . $flip . '! Вы проиграли -' . $amount . ' R';
        }
    }
}
