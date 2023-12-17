<?php

    namespace Revobot\Commands;
    use Revobot\Revobot;

    class ChatIdCmd extends BaseCmd
    {
        private Revobot $bot;
        const KEYS = ['chatid', 'чатид'];
        const IS_ENABLED = true;
        const HELP_DESCRIPTION = 'get chat id';

        public function __construct(string $input, Revobot $bot)
        {
            parent::__construct($input);
            $this->bot = $bot;
        }

        public function exec(): string
        {
            return (string)$this->bot->chat_id;
        }
    }
