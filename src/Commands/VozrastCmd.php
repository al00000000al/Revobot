<?php

namespace Revobot\Commands;

use Revobot\Revobot;
use Revobot\Util\PMC;

class VozrastCmd extends BaseCmd
{
    const KEYS = ['vozrast', 'возраст'];
    const IS_ENABLED = true;
    private Revobot $bot;
    const HELP_DESCRIPTION = 'Сколько сегодня мне лет';

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->setDescription('/vozrast напишите дату рождения');
        $this->bot = $bot;
    }

    public function exec(): string
    {
        $user_id = $this->bot->getUserId();
        if (empty($this->input)) {
            if (!$this->hasKey($user_id)) {
                return 'Я не знаю сколько тебе лет';
            }
        } else {
            $this->setKey($user_id, $this->input);
        }
        return (string)$this->calc((string)$this->getKey($user_id));
    }

    private function hasKey(int $user_id)
    {
        return (bool) PMC::get($this->_key($user_id)) !== null;
    }

    private function getKey(int $user_id)
    {
        return (string) PMC::get($this->_key($user_id));
    }

    private function setKey(int $user_id, string $date)
    {
        PMC::set($this->_key($user_id), $date);
    }

    private function _key(int $user_id)
    {
        return "USER_BYEAR_" . $user_id;
    }

    private function calc(string $bdateStr)
    {
        $bdateTimestamp = strtotime($bdateStr);
        if ($bdateTimestamp === false) {
            return "Неверный формат даты";
        }

        list($byear, $bmonth, $bday) = explode('-', date('Y-m-d', $bdateTimestamp));
        $thisYear = (int)date('Y');
        $thisMonth = (int)date('m');
        $thisDay = (int)date('d');
        $thisHour = (int)date('G');

        // Вычисляем текущий возраст
        $ageYears = $thisYear - $byear;
        $ageMonths = $thisMonth - $bmonth;
        $ageDays = $thisDay - $bday;
        if ($ageDays < 0) {
            $ageDays += (int)date('t', mktime(0, 0, 0, $thisMonth - 1, 1, $thisYear));
            $ageMonths--;
        }
        if ($ageMonths < 0) {
            $ageMonths += 12;
            $ageYears--;
        }

        // Вычисляем, сколько осталось до 100 лет
        $remainingYears = 100 - $ageYears;
        $remainingMonths = 11 - $ageMonths;
        $remainingDays = (int)date('t', mktime(0, 0, 0, $thisMonth, 1, $thisYear)) - $ageDays;
        $remainingHours = 23 - $thisHour;

        // Корректируем оставшиеся месяцы и дни
        if ($remainingDays < 0) {
            $remainingDays += (int)date('t', mktime(0, 0, 0, $thisMonth, 1, $thisYear));
            $remainingMonths--;
        }
        if ($remainingMonths < 0) {
            $remainingMonths += 12;
            $remainingYears--;
        }

        return "Сегодня вам: {$ageYears} лет, {$ageMonths} месяцев, {$ageDays} дней\nОсталось: {$remainingYears} лет, {$remainingMonths} месяцев, {$remainingDays} дней, {$remainingHours} часов";
    }
}
