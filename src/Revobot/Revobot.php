<?php

namespace Revobot;

use Revobot\Util\Curl;

class Revobot
{

    private int $chat_id;

    private string $provider;
    private string $message;

    /**
     * @param int $chat_id
     */
    public function setChatId(int $chat_id): void
    {
        $this->chat_id = $chat_id;
    }

    public function __construct($provider){
        $this->provider = $provider;
    }

    public function setMessage($message){
        $this->message = $message;
    }

    public function run(){
        if($this->provider === 'tg'){
            $response = CommandsManager::process($this->message);
            if($response !== null){
                $this->sendMessageTg($response);
            }
        }
    }

    public function sendMessageTg($response_text){
        $url = 'https://api.telegram.org/bot' . TG_KEY . '/sendMessage';
        Curl::post($url, [
           'chat_id' => $this->chat_id,
           'text' => $response_text
        ]);

    }








}