<?php

namespace Revobot\Commands;

use Revobot\Revobot;

class TalkCmd extends BaseCmd
{
    private Revobot $bot;

    public function __construct($input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
        $this->setDescription('/talk число от 3 до 100');
    }

    public function exec(): string
    {
        if (empty($this->input)) {
            return $this->description;
        }
        $value = (int)$this->input;
        if ($value < 3 || $value > 100) {
            return $this->description;
        }
        $this->bot->setTalkLimit($value);
        return 'Значение лимита установлено: ' . $value;
    }
}