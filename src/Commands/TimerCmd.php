<?php

namespace Revobot\Commands;

use Revobot\Commands\Custom\Prices;
use Revobot\Config;
use Revobot\Money\Revocoin;
use Revobot\Util\PMC;

class TimerCmd extends BaseCmd
{
    const KEYS = ['timer', 'таймер'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'Установка таймера ($)';

    public function __construct(string $input)
    {
        parent::__construct($input);
    }

    public function exec(): string
    {
        global $Bot;

        $args = explode(' ', $this->input, 3);

        if (count($args) < 3) {
            return 'Недостаточно аргументов. Формат: /timer дата время текст';
        }

        $date_time = strtotime($args[0] . ' ' . $args[1]);
        if (!$date_time) {
            return 'Некорректная дата или время. Формат: YYYY-MM-DD HH:MM';
        }

        $date_time2 = $date_time - self::_getUserTimeOffset();
        $text = $args[2];
        $chat_id = chatId();
        $user_id = userId();
        $rnd = mt_rand(100, 999);
        $timer_data = [
            'user' => $user_id,
            'text' => $text,
            'datetime' => $date_time2,
            'chat_id' => $chat_id,
            '_rnd' => $rnd
        ];

        PMC::set('timer_' . $date_time2 . '_' . $rnd, json_encode($timer_data));

        $revocoin =  (new Revocoin($Bot));
        $user_balance = $revocoin->getBalance($user_id);

        if ($user_balance < Prices::PRICE_TIMER) {
            return "Недостаточно ревокоинов (требуется 5 R)";
        }

        if (provider() == 'tg') {
            (new Revocoin($Bot))->transaction(Prices::PRICE_TIMER, Config::getInt('tg_bot_id'), $user_id);
        } elseif (provider() === 'vk') {
            (new Revocoin($Bot))->transaction(Prices::PRICE_TIMER, Config::getInt('vk_bot_id'), $user_id);
        }


        return 'Таймер установлен на ' . date('Y-m-d H:i', $date_time) . "\n -" . Prices::PRICE_TIMER . ' R';
    }

    private function _getUserTimeOffset()
    {
        $timezone =  PMC::get('user_timezone_tg' . userId());
        if (!$timezone) {
            return (TimeCmd::MSK_TZ) * 60 * 60;
        }
        return ((int)$timezone - TimeCmd::MSK_TZ) * 60 * 60;
    }
}
