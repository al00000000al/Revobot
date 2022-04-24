<?php

namespace Revobot\Money;

use Revobot\Revobot;

class Revocoin
{


    private Revobot $bot;
    private int $difficulty = 3;
    private \Memcache $pmc;

    const MAX_TRIES_DEFAULT = 40;

    const PMC_MONEY_TRIES_KEY = 'money_tries';
    const PMC_MONEY_LAST_BLOCK_KEY = 'money_last_block';

    /**
     * @param Revobot $bot
     */
    public function __construct(Revobot $bot){
        $this->bot = $bot;
        $this->pmc = $bot->pmc;
    }

    /**
     * @param int $to_user_id
     * @param int $from_user_id
     */
    public function send(int $to_user_id, int $from_user_id = 0){
        if($this->bot->provider === 'tg'){
            //get key
            //send resp
        }
    }


    /**
     * @return int
     */
    public function getMaxTries(): int
    {
        $prize_max_tries = (int) $this->pmc->get(self::PMC_MONEY_TRIES_KEY);
        if($prize_max_tries === 0){
            $prize_max_tries = self::MAX_TRIES_DEFAULT;
        }
        return $prize_max_tries;
    }

    /**
     * @return tuple(int, string)
     */
    public function getLastBlock()
    {
        $last_block = (string) $this->pmc->get(self::PMC_MONEY_LAST_BLOCK_KEY);
        if(empty($last_block)){
            return tuple(0, '');
        }
        list($block_id, $prev_hash) = $last_block;
        return tuple($block_id, $prev_hash);
    }


    // Майнинг фккоенов

    public function mining(){

        /*


        // @todo: instance_cache

        $prize_max_tries_future = fork(self::getMaxTries());
        $last_block_future = fork(self::getLastBlock());
        $prize_max_tries = wait($prize_max_tries_future);
        list($block_id, $prev_hash) = wait($last_block_future);


        for($i = 0; $i < $prize_max_tries; $i++){

        }

        if(substr($block->hash, 0, $this->difficulty) === str_repeat("0", $this->difficulty)){
            echo "Block mined: ".$block->hash."\n";
            return;
        }


        if (mcDecrKey('prize_' . $user_id, $coins_cnt)) {
            mcIncKey('prize_' . $to_user['user_id'], $coins_cnt);
            $last->from = $user_id;
            $last->previousHash = $prev_hash;

            $last_id++;

            $mc->set('prize_prev', $last->hash);
            $mc->set('last_prize_id', $last_id);
            return ($coins_cnt > 0 ? '+' : '') . $coins_cnt . ' фккоин у ' . $to_user['name'];
        }

            if ($result) {

                fc_send($data['chat']['chat_id'], $result);
            }

        */
    }
}