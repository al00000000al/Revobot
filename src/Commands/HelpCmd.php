<?php

namespace Revobot\Commands;

class HelpCmd extends BaseCmd
{

    const KEYS = ['help','хэлп','хлеп', 'помощь'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'Помощь';

    /**
     * @return string
     */
    public function exec(): string
    {

        return "Список команд бота:
/alive - состояние
/alias - создать алиас (10R)
/balance - ваш баланс
/bash - рандомные истории
/chat - случайный канал
/command - создать команду (20R)
/echo - печатать
/exchange - курс валют
/infa - вероятность события
/mycommands - мои команды
/or - слово или слово
/pukvy - риска миса
/rand - число от до
/rsend - отправить случ. польз.
/send - отправить пользователю
/time - время в мск
/yn - да или нет?
/when - когда";

    }
}
