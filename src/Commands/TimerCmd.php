<?php

namespace Revobot\Commands;

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
        $args = explode(' ', $this->input, 3);

        if (count($args) < 3) {
            return 'Недостаточно аргументов. Формат: /timer дата время текст';
        }

        $date_time = strtotime($args[0] . ' ' . $args[1]);
        if (!$date_time) {
            return 'Некорректная дата или время. Формат: YYYY-MM-DD HH:MM';
        }

        $text = $args[2];
        $chat_id = chatId();
        $rnd = mt_rand(100, 999);
        $timer_data = [
            'user' => userId(),
            'text' => $text,
            'datetime' => $date_time,
            'chat_id' => $chat_id,
            '_rnd' => $rnd
        ];

        PMC::set('timer_' . $date_time . '_' . $rnd, json_encode($timer_data));

        return 'Таймер установлен на ' . date('Y-m-d H:i', $date_time);
    }
}
