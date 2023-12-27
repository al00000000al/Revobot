<?php

namespace Revobot\Commands;

use Revobot\Config;
use Revobot\Games\Quiz;
use Revobot\Revobot;

class QuestionCmd extends BaseCmd
{
    const KEYS = ['vopros', 'question', 'вопрос'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'Вопросы на коины';
    private Revobot $bot;

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
    }

    public function exec(): string
    {
        // $question = (new Quiz($this->bot))->getQuestion();
        // return (string)$question['question'];
        $base_path = Config::get('base_path');
        $chat_id = (int)$this->bot->chat_id;
        exec("cd {$base_path}/scripts && php gptdq.php $chat_id > /dev/null 2>&1 &");
        return '';
    }
}
