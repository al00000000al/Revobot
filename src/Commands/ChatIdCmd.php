<?php

namespace Revobot\Commands;

class ChatIdCmd extends BaseCmd
{
    const KEYS = ['chatid', 'чатид'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'get chat id';

    public function __construct(string $input)
    {
        parent::__construct($input);
    }

    public function exec(): string
    {
        return (string)chatId();
    }
}
