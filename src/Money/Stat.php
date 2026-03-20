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

        if (!$chat) {
            return 'Никого еще нет в рейтинге';
        }

        $users = [];
        $usernames = [];

        $conn = new_rpc_connection('127.0.0.1', 11209, 0, 6);

        if (!$conn) {
            return 'RPC connection error';
        }

        $queries = [];

        foreach ($chat as $user) {

            if ($user === $this->bot->getBotId() || -$user === $this->bot->getBotId()) {
                $user = -abs($user);
            }

            $queries[] = [
                'memcache.get',
                Revocoin::PMC_MONEY_USER_BALANCE_KEY . provider() . $user
            ];

            $queries[] = [
                'memcache.get',
                provider() . '_username' . $user
            ];
        }

        $responses = [];

        foreach (array_chunk($queries, 300) as $chunk) {
            $ids = rpc_tl_query($conn, $chunk);

            if (!$ids) {
                continue;
            }

            $result = rpc_tl_query_result($ids);

            if ($result) {
                $responses = array_merge($responses, $result);
            }
        }

        $i = 0;

        foreach ($chat as $user) {

            $balanceIndex = $i * 2;
            $usernameIndex = $i * 2 + 1;

            if (isset($responses[$balanceIndex]['result']['value'])) {
                $users[$user] = (float)$responses[$balanceIndex]['result']['value'];
            } else {
                $users[$user] = 0;
            }

            if (isset($responses[$usernameIndex]['result']['value'])) {
                $usernames[$user] = (string)$responses[$usernameIndex]['result']['value'];
            } else {
                $usernames[$user] = $this->getUsername((int)$user);
            }

            $i++;
        }

        return $this->format($users, $usernames);
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
        if ($this->bot->provider == 'tg') {
            if ($user_id === $this->bot->getBotId()) {
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

            // }
        } elseif ($this->bot->provider === 'vk') {
            $username = $this->bot->getUserNick() ?? 'unk';
        }
        PMC::set($this->bot->provider . '_username' . $user_id, $username ? $username : (string)$user_id);
        return (string)$username;
    }



    /**
     * @return string
     */
    public function getStatCacheKey(): string
    {
        return 'stat_' . provider() . chatId();
    }
}
