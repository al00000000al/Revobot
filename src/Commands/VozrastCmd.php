<?php

namespace Revobot\Commands;

use Revobot\Revobot;
use Revobot\Util\PMC;

class VozrastCmd extends BaseCmd
{
    const KEYS = ['vozrast', 'возраст'];
    const IS_ENABLED = true;
    private Revobot $bot;
    const HELP_DESCRIPTION = 'Сколько сегодня мне лет (нецелое)';

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->setDescription('/vozrast напишите свой год рождения от Рождества Христова');
        $this->bot = $bot;
    }

    public function exec(): string
    {
        if (!$this->input) {
            if (!$this->hasKey()) {
                return $this->description;
            } else {
                $byear = (int) $this->getKey();
                return $this->calc($byear);
            }
        }
        $byear = (int) $this->input;
        $this->setKey($byear);
        return $this->calc($byear);
    }

    private function hasKey()
    {
        return (bool) PMC::get($this->getYearKey($this->bot->getUserId())) !== null;
    }

    private function getKey()
    {
        return (int) PMC::get($this->getYearKey($this->bot->getUserId()));
    }

    private function setKey($byear)
    {
        PMC::set($this->getYearKey($this->bot->getUserId()), $byear);
    }

    private function getYearKey(int $user_id)
    {
        return "USER_BYEAR_" . $user_id;
    }

    private function calc(int $byear)
    {
        $days = date('L') ? 366 : 365;
        $calc = (date('z') / $days) + date('Y') - $byear;
        $calc2 = 100 - $calc;
        return "Сегодня вам: {$calc} лет\nОсталось: {$calc2} в лучшем случае";
    }
}
