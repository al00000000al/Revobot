<?php

namespace Revobot\Commands;

use Revobot\Config;
use Revobot\Games\AI\GptPMC;
use Revobot\Revobot;
use Revobot\Util\Curl;

class SummarizeCmd extends BaseCmd
{
    const KEYS = ['summarize', 'прочитай', 'статья'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = '/summarize ссылка';
    private Revobot $bot;

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->setDescription('/summarize Краткое описание по ссылке');
        $this->bot = $bot;
    }

    public function exec(): string
    {
        if (empty($this->input)) {
            return $this->description;
        }
        $user_id = userId();
        $chat_id = chatId();
        $data = strip_tags(Curl::get(trim($this->input)));
        $message_id = $this->bot->raw_data['message_id'];
        $GptPMC = new GptPMC($user_id, $this->bot->provider);
        $GptPMC->setInput('Что тут? Напиши очень кратко на русском языке.' . PHP_EOL . $data);
        $base_path = Config::get('base_path');
        exec("cd {$base_path}/scripts && php gptda.php $chat_id $user_id $message_id > /dev/null 2>&1 &");
        return '';
    }
}
