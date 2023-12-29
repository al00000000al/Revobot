<?php

namespace Revobot\Commands;

use Revobot\Revobot;
use Revobot\Services\Providers\Tg;

class DelmsgCmd extends BaseCmd
{
    const KEYS = ['delmsg'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'Удааление сообщения бота';
    private Revobot $bot;

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->setDescription('Ответь на сообщение бота /delmsg');
        $this->bot = $bot;
    }

    public function exec(): string
    {

        if (isset($this->bot->raw_data['reply_to_message']) && isset($this->bot->raw_data['reply_to_message']['message_id'])) {
            $msg_id = (int)$this->bot->raw_data['reply_to_message']['message_id'];
            Tg::deleteMessage(chatId(), $msg_id);
            return '';
        }
        return $this->description;
    }
}
