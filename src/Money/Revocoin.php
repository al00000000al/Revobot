<?php

namespace Revobot\Money;

use Revobot\Revobot;
use Revobot\Util\Hash;
use Revobot\Util\PMC;

class Revocoin
{


    private Revobot $bot;
    private int $difficulty = 3;

    const MONEY_VERSION = 2;
    const MAX_TRIES_DEFAULT = 30;
    const TRANSACTION_COMMISSION = 0.03; // 3%

    const PMC_MONEY_TRIES_KEY = 'money_tries';
    const PMC_MONEY_LAST_BLOCK_KEY = 'money_last_block';
    const PMC_MONEY_USER_BALANCE_KEY = 'money_'; // .$provider.$id
    const PMC_MONEY_LOCKED_KEY = 'money_locked_'; // .$provider.$id
    const PMC_MONEY_BLOCK_KEY = 'money_block_'; // .$block_id
    const PMC_MONEY_STAT_CHAT_KEY = 'money_stat_'; // .$chat_id.'_'.$provider.$id

    /**
     * @param Revobot $bot
     */
    public function __construct(Revobot $bot)
    {
        $this->bot = $bot;
    }

    /**
     * @param int $to_user_id
     * @param int $from_user_id
     * @param float $amount
     * @return bool
     */
    public function send(int $to_user_id, int $from_user_id, float $amount): bool
    {
        if ($this->bot->provider === 'tg') {
            return $this->transaction($amount, $to_user_id, $from_user_id);
        }
        return false;
    }


    /**
     * @return int
     */
    public function getMaxTries(): int
    {
        $prize_max_tries = (int)PMC::get(self::PMC_MONEY_TRIES_KEY);
        if ($prize_max_tries === 0) {
            $prize_max_tries = self::MAX_TRIES_DEFAULT;
        }
        return $prize_max_tries;
    }

    /**
     * @return mixed[]
     */
    public function getLastBlock(): array
    {
        $last_block = PMC::get(self::PMC_MONEY_LAST_BLOCK_KEY);

        list($block_id, $prev_hash) = $last_block;
        return [$block_id, $prev_hash];
    }


    /**
     * @param mixed $block_id
     * @param mixed $prev_hash
     * @return bool
     */
    public function setLastBlock($block_id, $prev_hash): bool
    {
        PMC::set(self::PMC_MONEY_LAST_BLOCK_KEY, [$block_id, $prev_hash]);
        return true;
    }


    /**
     * @param string $params
     * @return string
     */
    private function generate(string $params): string
    {
        return Hash::generate($params);
    }

    /**
     * @param int $id
     * @param string $params
     */
    public function saveBlock(int $id, string $params)
    {
        PMC::set(self::PMC_MONEY_BLOCK_KEY . $this->bot->provider . $id, $params);
    }

    /**
     * @param string $hash
     * @return bool
     */
    public function validateBlock(string $hash): bool
    {
        if (substr($hash, 0, $this->difficulty) === str_repeat("0", $this->difficulty)) {
            //dbg_echo("Block mined: " . $hash . "\n");
            return true;
        }
        return false;
    }


    /**
     * @param float $amount
     * @param int $to_user_id
     * @param int $from_user_id
     * @return bool
     */
    public function transaction(float $amount, int $to_user_id, int $from_user_id = 0): bool
    {

        if ($amount < 0) {
            return false;
        }

        if ($to_user_id === $from_user_id) {
            return false;
        }

        if ($from_user_id !== 0) {
            $from_user_balance = $this->getBalance($from_user_id);
            if ($from_user_balance < $amount) {
                return false;
            }

            $this->updateBalance($from_user_id, $from_user_balance, -$amount);

            // commission for transaction
            $commission = $amount * self::TRANSACTION_COMMISSION;
            $amount -= $commission;
            $this->updateBalance($this->bot->getTgBotId(), $this->getBalance($this->bot->getTgBotId()), $commission);
        }

        $to_user_balance = (float)$this->getBalance($to_user_id);

        $this->updateBalance($to_user_id, $to_user_balance, $amount);
        return true;
    }

    /**
     * @param int $user
     * @return float
     */
    public function getBalance(int $user): float
    {
        return (float)(PMC::get(self::PMC_MONEY_USER_BALANCE_KEY . $this->bot->provider . $user));
    }


    /**
     * @param int $user
     * @param float $old_balance
     * @param float $balance
     * @return bool
     */
    public function updateBalance(int $user, float $old_balance, float $balance): bool
    {
        $new_balance = $old_balance + $balance;
        PMC::set(self::PMC_MONEY_USER_BALANCE_KEY . $this->bot->provider . $user, $new_balance);
        return true;
    }





    // Майнинг фккоенов

    /**
     * @param int $to_user_id
     * @param int $from_user_id
     * @param string $text
     * @return mixed[]
     */
    public function mining(int $to_user_id, int $from_user_id = 0, string $text = ''): array
    {
        // @todo: instance_cache

        $prize_max_tries = self::MAX_TRIES_DEFAULT;
        $last_block = $this->getLastBlock();
        $block_id = (int)$last_block[0];
        $prev_hash = $last_block[1];

        $next_id = $block_id + 1;
        $prize = 100.0;
        $time = time();
        $nonce = mt_rand(0, PHP_INT_MAX);

        for ($i = 0; $i < $prize_max_tries; $i++) {

            $params = [
                'id' => $next_id,
                'time' => $time,
                'amount' => $prize,
                'from' => $from_user_id,
                'to' => $to_user_id,
                'prev_hash' => $prev_hash,
                'version' => self::MONEY_VERSION,
                'nonce' => $nonce,
                'text' => $text,
            ];

            $params_str = (string)json_encode($params);

            $hash = $this->generate($params_str);

            if ($this->validateBlock($hash)) {

                $result = $this->transaction($prize, $to_user_id, $from_user_id);

                if ($result) {
                    $this->saveBlock($next_id, $params_str);
                    $this->setLastBlock($next_id, $hash);

                    return ['id' => $next_id, 'amount' => $prize];
                }

                return [];
            }
            $prize /= 1.5;
        }

        return [];
    }
}
