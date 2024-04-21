<?php

namespace Revobot\Money;

use Revobot\Config;
use Revobot\Revobot;
use Revobot\Services\Providers\Tg;
use Revobot\Util\PMC;

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

        $stat_key = $this->getStatCacheKey();

        $cached = instance_cache_fetch(StatCached::class, $stat_key);
        if (!$cached) {
            $users = [];
            $usernames = [];

            $conn = new_rpc_connection('127.0.0.1', 11209, 0, 6);
            $balancesQuery = [];
            $usernamesQuery = [];

            foreach ($chat as $user) {
                $balancesQuery[]  = ['memcache.get', Revocoin::PMC_MONEY_USER_BALANCE_KEY . provider() . $user];
                $usernamesQuery[] = ['memcache.get', provider() . '_username' . $user];
            }

            $query_ids = rpc_tl_query($conn, $balancesQuery);
            $responseBalances = rpc_tl_query_result($query_ids);
            $query_ids = rpc_tl_query($conn, $usernamesQuery);
            $responseUsernames = rpc_tl_query_result($query_ids);
            $i = 0;
            foreach ($chat as $user) {
                if (isset($responseBalances[$i]['result']['value'])) {
                    $users[$user] = (float)$responseBalances[$i]['result']['value'];
                } else {
                    $users[$user] = 0;
                }
                if (isset($responseUsernames[$i]['result']['value'])) {
                    $usernames[$user] = (string)$responseUsernames[$i]['result']['value'];
                } else {
                    $usernames[$user] = $this->getUsername((int)$user);
                }
                $i++;
            }

            if (in_array(userId(), Config::getArr('tg_bot_admins'))) {
                Tg::sendMessage(chatId(), (string)json_encode($responseBalances) . (string)json_encode($responseUsernames));
            }


            /*
            $revocoin = new Revocoin($this->bot);
            foreach ($chat as $user) {
                $users[$user] = $revocoin->getBalance((int)$user);
                $usernames[$user] = $this->getUsername((int)$user);
            }
            */

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
     * [Ссылка](https://example.com)
     * @param int $user_id
     * @return string
     */
    private function getUsername(int $user_id): string
    {
        // $username = PMC::get('tg_username' . $user_id);
        // if (!$username) {
        if ($user_id === $this->bot->getTgBotId()) {
            return '[Therevoluciabot](https://t.me/Therevoluciabot)';
        }

        $chat_member = $this->bot->getChatMemberTg($user_id);

        if (!isset($chat_member['result'])) {
            return '';
        }

        if (isset($chat_member['result']['user']['username'])) {
            $username = '[' . $chat_member['result']['user']['username'] . '](https://t.me/' . $chat_member['result']['user']['username'] . ')';
        } else {
            $username = (string)$user_id;
        }
        PMC::set('tg_username' . $user_id, $username);
        // }
        return (string)$username;
    }



    /**
     * @return string
     */
    public function getStatCacheKey(): string
    {
        return 'stat_' . chatId();
    }
}
