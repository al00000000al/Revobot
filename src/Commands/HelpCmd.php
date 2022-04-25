<?php

namespace Revobot\Commands;

class HelpCmd extends BaseCmd
{
    /**
     * @return string
     */
    public function exec(): string
    {

        return "Список комманд бота:
/alive - состояние
/bash - рандомные истории
/chat - случайный канал
/infa - вероятность события
/pukvy - риска миса
/rand число от до
/yn - да или нет?
/when - когда";

    }
}
