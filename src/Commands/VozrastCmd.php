<?php

namespace Revobot\Commands;
use Revobot\Revobot;

class VozrastCmd extends BaseCmd
{
    const KEYS = ['vozrast','возраст'];
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
        if (!$this->input){
            if(!self::hasKey())
            {
                return $this->description;
            }
            else
            {
                $byear = (int) self::getKey();
                return self::calc($byear);
            }
        }
        $byear = (int) $this->input;
        self::setKey($byear);
        return self::calc($byear);

    }

    private function hasKey()
    {
        return (bool) $this->bot->pmc->get(self::getYearKey($this->bot->getUserId())) != null;
    }

    private function getKey()
    {
       return (int) $this->bot->pmc->get(self::getYearKey($this->bot->getUserId()));
    }

    private function setKey($byear){
        $this->bot->pmc->set(self::getYearKey($this->bot->getUserId()), $byear);
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
