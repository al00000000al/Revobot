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
        return "Я думаю что это: @" . $this->getUser(Math::sum($this->wordsToNum()));
    }

    private function getChatUsers(){
        return $this->bot->loadUsernamesChat();
    }

    /**
     * @param int $rate
     * @return string
     */
    private function getUser(int $rate): string
    {

        $rate = $rate + 1 * 12345;

        $chat = $this->getChatUsers();
        if(!$chat){
            return 'null';
        }
        $chat_cnt = count($chat);

        $user_position = $rate % $chat_cnt;

        $i = 0;
        foreach($chat as $user => $id){
            if($i === $user_position){
                return (string)$user;
            }
            $i++;
        }
        return 'null';
    }
}