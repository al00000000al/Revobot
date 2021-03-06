<?php

namespace Revobot\Commands;

use Revobot\Games\Quiz;
use Revobot\Revobot;

class QuestionCmd extends BaseCmd
{
    private Revobot $bot;

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
    }

    public function exec(): string
    {
        $question = (new Quiz($this->bot))->getQuestion();
        return (string)$question['question'];
    }
}