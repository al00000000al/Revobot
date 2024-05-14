<?php

namespace Revobot\JobWorkers\Requests;

use Revobot\Config;
use Revobot\Games\AI\Gpt as AIGpt;
use Revobot\JobWorkers\JobWorkerNoReply;
use Revobot\Services\Providers\Tg;
use Revobot\Util\Curl;

class Gpt extends JobWorkerNoReply
{

    /** @var mixed */
    public $options;

    /**
     * @param mixed $arg_to_print
     */
    public function __construct($arg_to_print)
    {
        $this->options = $arg_to_print;
    }


    function handleRequest(): void
    {
        $response = AIGpt::generate((string)$this->options['input'], (int) $this->options['user_id'], (string) $this->options['provider'], (bool)$this->options['clear_all'], (string)$this->options['model']);
        $this->sendMessageTg($response);
    }



    public function sendMessageTg(string $response_text, $parse_mode = null)
    {
        if ($response_text[0] == '@') {
            $response_text = str_replace('@', '', $response_text);
        }
        $res = Tg::sendMessage((int) $this->options['chat_id'], $response_text, $parse_mode);
        dbg_echo(implode(',', [implode(',', $res), (int) $this->options['chat_id'], $response_text]));
    }
}
