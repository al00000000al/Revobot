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
/when - когда
/infa - вероятность события
/yn - да или нет?
/pukvy - риска миса
/rand число от до";

    }
}