<?php

namespace Revobot\Games\Predictor;

use Revobot\Revobot;
use Revobot\Util\Math;

class Who extends PredictBase
{
    private Revobot $bot;

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
    }

    /**
     * @return string
     */
    public function calc(): string
    {
        return "Я думаю что это: @" . $this->getUser($this->getRandom());
    }

    public function calcUserId()
    {
        return $this->getUserFull($this->getRandom());
    }

    public function getRandom(): int
    {
        return Math::sum($this->wordsToNum());
    }

    private function getChatUsers()
    {
        return $this->bot->loadUsernamesChat();
    }

    private function getUserFull(int $rate): array
    {
        $rate += 1 * 12345;

        $chat = $this->getChatUsers();
        if (!$chat) {
            return [0, ''];
        }
        $chat_cnt = count($chat);

        $user_position = $rate % $chat_cnt;

        $i = 0;
        foreach ($chat as $user => $id) {
            if ($i === $user_position) {
                return [$id, (string)$user];
            }
            $i++;
        }
        return [0, ''];
    }

    /**
     * @param int $rate
     * @return string
     */
    private function getUser(int $rate): string
    {
        $rate += 1 * 12345;

        $chat = $this->getChatUsers();
        if (!$chat) {
            return 'null';
        }
        $chat_cnt = count($chat);

        $user_position = $rate % $chat_cnt;

        $i = 0;
        foreach ($chat as $user => $_) {
            if ($i === $user_position) {
                return (string)$user;
            }
            $i++;
        }
        return 'null';
    }
}
