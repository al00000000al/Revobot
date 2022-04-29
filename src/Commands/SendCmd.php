<?php

namespace Revobot\Commands;

use Revobot\Money\Revocoin;
use Revobot\Revobot;

class SendCmd extends BaseCmd
{
    private Revobot $bot;

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->setDescription('/send @юзер <сумма>');
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
        $to_user_id = self::getId($username);
        $amount = (float)$params[1];

        $result = (new Revocoin($this->bot))->send($to_user_id, $this->bot->getUserId(), $amount);
        if (!$result) {
            return $this->description;
        }
        $amount = $amount - ($amount * Revocoin::TRANSACTION_COMMISSION);
        return '+' . $amount . ' R у @' . $username;
    }

    /**
     * @param string $username
     * @return int
     */
    private function getId(string $username): int
    {
        $chat_member = $this->bot->getChatMemberByUsernameTg($username);

        if (!isset($chat_member['result'])) {
            return 0;
        }

        return $chat_member['result']['user']['id'] ?? 0;
    }
}