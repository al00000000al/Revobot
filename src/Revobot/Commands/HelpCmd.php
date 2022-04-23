<?php

namespace Revobot\Commands;

class HelpCmd extends BaseCmd
{
    public function exec(): string
    {
        return <<<string
Список комманд бота:
/alive - состояние
/bash - рандомные истории
/who - кто?
/infa - вероятность события
/yn - да или нет?
/pukvy - риска миса
/rand число от до
string;

    }
}