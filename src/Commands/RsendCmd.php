<?php

namespace Revobot\Commands;

use Revobot\Money\Revocoin;
use Revobot\Revobot;

class RsendCmd extends BaseCmd
{

    const KEYS = ['rsend', 'рсенд'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'Отправить R случ. польз.';

    private Revobot $bot;

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->setDescription('/rsend <сумма>');
        $this->bot = $bot;
    }

    public function exec(): string
    {
        if (empty($this->input)) {
            return $this->description;
        }

        $amount = (float)$this->input;

        $chat_members = $this->bot->loadChat();
        if (!$chat_members) {
            return $this->description;
        }

        $choice = (int) $chat_members[mt_rand(0, count($chat_members) - 1)];
        $result = (new Revocoin($this->bot))->send($choice, userId(), $amount);
        if (!$result) {
            return $this->description;
        }
        $username = $this->getUsername($choice);
        $amount -= ($amount * Revocoin::TRANSACTION_COMMISSION);
        return '+' . $amount . ' R у ' . $username;
    }

    /**
     * @param int $user_id
     * @return string
     */
    private function getUsername(int $user_id): string
    {
        $chat_member = $this->bot->getChatMemberTg($user_id);

        if (!isset($chat_member['result'])) {
            return '';
        }

        if (isset($chat_member['result']['user']['username'])) {
            $username = '@' . $chat_member['result']['user']['username'];
        } else {
            $username = (string)$user_id;
        }
        return $username;
    }
}
