<?php

namespace Revobot\Commands;

use Revobot\Money\Revocoin;
use Revobot\Revobot;

class SendCmd extends BaseCmd
{

    const KEYS = ['send', 'сенд'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'Отправить R польз.';
    private Revobot $bot;

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->setDescription("/send @юзер <сумма>\n/send telegram_id <сумма>");
        $this->bot = $bot;
    }

    public function exec(): string
    {
        if (empty($this->input)) {
            return $this->description;
        }
        $params = explode(' ', $this->input);
        if (count($params) !== 2) {
            return $this->description;
        }

        $username = $params[0];

        if ($username[0] == '@') {
            $username = str_replace('@', '', $username);
            $to_user_id = $this->getId($username);
        } else {
            $to_user_id = (int)$username;
        }
        if ($to_user_id === 0) {
            return $this->description;
        }
        $amount = (float)$params[1];

        $result = (new Revocoin($this->bot))->send($to_user_id, userId(), $amount);
        if (!$result) {
            return $this->description;
        }
        $amount -= ($amount * Revocoin::TRANSACTION_COMMISSION);
        return '+' . $amount . ' R у ' . $username;
    }

    /**
     * @param string $username
     * @return int
     */
    private function getId(string $username): int
    {
        $chat_usernames = $this->bot->loadUsernamesChat();
        if (array_key_exists($username, $chat_usernames)) {
            return (int)$chat_usernames[$username];
        }
        return 0;
    }
}
