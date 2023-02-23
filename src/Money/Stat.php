<?php

namespace Revobot\Money;

use Revobot\Revobot;

class Stat
{
    private Revobot $bot;

    public function __construct(Revobot $bot)
    {
        $this->bot = $bot;
    }

    public function get(): string
    {
        $chat = $this->bot->loadChat();
        if (empty($chat)) {
            return 'Никого еще нет в рейтинге';
        }

        // Add bot to stat
        $chat[] = -TG_BOT_ID;

        $stat_key = $this->getStatCacheKey();

        $cached = instance_cache_fetch(StatCached::class, $stat_key);
        if (!$cached) {
            $revocoin = new Revocoin($this->bot);
            $users = [];
            $usernames = [];
            foreach ($chat as $user) {
                $users[$user] = $revocoin->getBalance((int)$user);
                $usernames[$user] = $this->getUsername((int)$user);
            }

            $cached = new StatCached($users, $usernames);
            instance_cache_store($this->getStatCacheKey(), $cached, 60);
        }


        return $this->format($cached->users, $cached->usernames);
    }

    /**
     * @param float[] $users
     * @param string[] $usernames
     * @return string
     */
    public function format(array $users, array $usernames): string
    {
        $result = "Рейтинг: \n";

        arsort($users);

        $i = 1;
        foreach ($users as $user_id => $amount) {
            if (array_key_exists($user_id, $usernames)) {
                $username = $usernames[$user_id];
            } else {
                $username = $user_id;
            }
            $result .= "{$i}) {$username}: {$amount} R\n";
            $i++;
        }

        return $result;
    }

    /**
     * @param int $user_id
     * @return string
     */
    private function getUsername(int $user_id): string
    {
        if($user_id === -TG_BOT_ID){
            return '@Therevoluciabot';
        }

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



    /**
     * @return string
     */
    public function getStatCacheKey(): string
    {
        return 'stat_' . $this->bot->chat_id;
    }
}
