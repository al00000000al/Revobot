<?php

namespace Revobot;

use Revobot\Money\Revocoin;
use Revobot\Util\Curl;

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
     *
     */
    public function run()
    {
        if ($this->provider === 'tg') {

            $mining_future = fork((new Revocoin($this))->mining($this->getUserId()));
            $response = CommandsManager::process($this);

           // dbg_echo($response."\n");
            if ($response) {
                $this->sendMessageTg($response);
            }
            $mining_result = wait($mining_future);
            if(!empty($mining_result)) {
                $this->sendMessageTg('+'.$mining_result['amount'].' R у @'.$this->getUserNick()."\nBlock #".$mining_result['id']);

            }

        }
    }

    /**
     * @param string $response_text
     */
    public function sendMessageTg(string $response_text)
    {
        $url = 'https://api.telegram.org/bot' . $this->tg_key . '/sendMessage';
        Curl::post($url, [
            'chat_id' => $this->chat_id,
            'text' => $response_text
        ]);
    }

    /**
     * @param mixed $raw_data
     */
    public function setRawData(array $raw_data): void
    {
        $this->raw_data = $raw_data;
    }


}