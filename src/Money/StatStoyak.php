<?php

namespace Revobot\Money;

use Revobot\Commands\StoyakCmd;
use Revobot\Revobot;
use Revobot\Util\PMC;

class StatStoyak
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
            return 'Ни у кого еще не встал';
        }

        $stat_key = self::getStatCacheKey($this->bot->chat_id);

        $cached = instance_cache_fetch(StatCached::class, $stat_key);
        if (!$cached) {
            $users = [];
            $usernames = [];
            foreach ($chat as $user) {
                $users[$user] = (int) PMC::get(StoyakCmd::getUserChatKey($this->bot->chat_id, $user));
                $usernames[$user] = $this->getUsername((int)$user);
            }

            $cached = new StatCached($users, $usernames);
            instance_cache_store(self::getStatCacheKey($this->bot->chat_id), $cached, 60);
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
        $result = "Рейтинг стояков: \n";

        arsort($users);

        $i = 1;
        foreach ($users as $user_id => $amount) {
            if ($amount == 0) {
                continue;
            }
            if (array_key_exists($user_id, $usernames)) {
                $username = $usernames[$user_id];
            } else {
                $username = $user_id;
            }
            $result .= "{$i}) {$username}: {$amount}\n";
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
    public static function getStatCacheKey($chat_id): string
    {
        return 'stoyak_stat_' . $chat_id;
    }
}
