<?php

namespace Revobot;

use Revobot\Money\Revocoin;
use Revobot\Neural\Answers;
use Revobot\Util\Curl;
use Revobot\Util\Dummy;

class Revobot
{

    public int $chat_id;

    public string $provider;
    public string $message;

    /** @var $raw_data mixed */
    public $raw_data;

    private string $tg_key = '';

    /** @var $pmc \Memcache */
    public \Memcache $pmc;
    private string $parse_mode;

    private const PMC_TALK_LIMIT_KEY = 'talk_limit_'; // $provider.$chat
    private const PMC_MSG_HISTORY_KEY = 'msg_history_'; // $provider.$chat
    private const PMC_USERNAMES_CHAT_KEY = 'usernames_chat_'; // $provider.$chat
    private const PMC_CHAT_KEY = 'chat_'; // $provider.$chat
    private const DEFAULT_TALK_LIMIT = 90;

    /**
     * @param string $parse_mode
     */
    public function setParseMode(string $parse_mode): void
    {
        $this->parse_mode = $parse_mode;
    }

    /**
     * @param \Memcache $pmc
     */
    public function setPmc(\Memcache $pmc): void
    {
        $this->pmc = $pmc;
    }

    /**
     * @param string $tg_key
     */
    public function setTgKey(string $tg_key): void
    {
        $this->tg_key = $tg_key;
    }

    /**
     * @param int $chat_id
     */
    public function setChatId(int $chat_id): void
    {
        $this->chat_id = $chat_id;
    }


    /**
     * @param string $provider
     */
    public function __construct(string $provider)
    {
        $this->provider = $provider;
    }

    /**
     * @param string $message
     */
    public function setMessage(string $message)
    {
        $this->message = $message;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        if ($this->provider == 'tg') {
            if (isset($this->raw_data['from']['id'])) {
                return (int)$this->raw_data['from']['id'];
            }
            return 0;
        }
        return 0;
    }

    /**
     * @return string
     */
    public function getUserNick(): string
    {
        if ($this->provider == 'tg') {
            if (isset($this->raw_data['from']['username'])) {
                return (string)$this->raw_data['from']['username'];
            }
            return 'null';
        }
        return '';
    }

    /**
     * @return int
     */
    public function getTalkLimit(): int
    {
        $response = (int) $this->pmc->get(self::PMC_TALK_LIMIT_KEY . $this->provider . $this->chat_id);
        if(!$response){
            return self::DEFAULT_TALK_LIMIT;
        }
        return $response;
    }

    public function setTalkLimit(int $talk_limit)
    {
        $this->pmc->set(self::PMC_TALK_LIMIT_KEY . $this->provider . $this->chat_id, $talk_limit);
    }

    /**
     *
     */
    public function run()
    {
        if ($this->provider === 'tg') {

            $mining_future = fork((new Revocoin($this))->mining($this->getUserId()));
            $talk_limit = $this->getTalkLimit();

            $has_bot_response = (time() % $talk_limit) === 0;

            $response = CommandsManager::process($this);

            if ($response) {
                $this->sendMessageTg($response);
                $this->addUserChat();
            }
            $mining_result = wait($mining_future);
            if (!empty($mining_result)) {
                $this->sendMessageTg('+' . $mining_result['amount'] . ' R у @' . $this->getUserNick() . "\nBlock #" . $mining_result['id']);
            }

            if ($has_bot_response) {

                $bot_answer = Answers::getAnswer('- ' . $this->message . "\n - ");
                if (!empty($bot_answer)) {

                    $this->sendMessageTg((string)$bot_answer);
                }
            }


        }
    }


    /**
     * @return string
     */
    public function getHistoryMsg(): string
    {
        return (string)$this->pmc->get(self::PMC_MSG_HISTORY_KEY . $this->provider . $this->chat_id);
    }

    /**
     * @param string $msg
     * @return string
     */
    public function setHistoryMsg(string $msg): string
    {
        return (string)$this->pmc->set(self::PMC_MSG_HISTORY_KEY . $this->provider . $this->chat_id, $msg);
    }


    /**
     * @param string $response_text
     * @todo
     */
    public function sendMessageTg(string $response_text)
    {
        $url = 'https://api.telegram.org/bot' . $this->tg_key . '/sendMessage';

        if (substr($response_text, 0, 1) == '@') {
            $response_text = str_replace('@', '', $response_text);
        }

        Curl::post($url, [
            'chat_id' => $this->chat_id,
            'text' => $response_text,
            'parse_mode' => $this->parse_mode,
        ]);
    }


    /**
     * @param int $user_id
     * @return mixed
     * @todo
     */
    public function getChatMemberTg(int $user_id)
    {

        $url = 'https://api.telegram.org/bot' . $this->tg_key . '/getChatMember';
        return Curl::post($url, [
            'chat_id' => $this->chat_id,
            'user_id' => $user_id,
        ]);
    }


    /**
     * @param string $username
     * @return mixed
     * @todo
     */
    public function getChatMemberByUsernameTg(string $username)
    {

        $url = 'https://api.telegram.org/bot' . $this->tg_key . '/getChatMember';
        return Curl::post($url, [
            'chat_id' => $this->chat_id,
            'user_id' => $username,
        ]);
    }


    /**
     * @param mixed $raw_data
     */
    public function setRawData(array $raw_data): void
    {
        $this->raw_data = $raw_data;
    }

    /**
     * @return mixed
     */
    public function loadChat(): array
    {
        return $this->pmc->get(self::PMC_CHAT_KEY . $this->provider . $this->chat_id);
    }

    /**
     * @return mixed
     */
    public function loadUsernamesChat(): array
    {
        return $this->pmc->get(self::PMC_USERNAMES_CHAT_KEY . $this->provider . $this->chat_id);
    }

    public function addUserChat()
    {
        $user = $this->getUserId();
        $chat = $this->loadChat();
        $usernames_chat = $this->loadUsernamesChat();
        if (!in_array($user, $chat)) {
            $chat[] = $user;
            $this->pmc->set(self::PMC_CHAT_KEY . $this->provider . $this->chat_id, $chat);
        }

        $user_nick = $this->getUserNick();
        if (!array_key_exists($user, $usernames_chat) || $usernames_chat[$user_nick] !== $user) {
            $usernames_chat[$user_nick] = $user;
            $this->pmc->set(self::PMC_USERNAMES_CHAT_KEY . $this->provider . $this->chat_id, $usernames_chat);
        }
    }
}
