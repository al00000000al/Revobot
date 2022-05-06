<?php

namespace Revobot\Commands;

use Revobot\Games\Quiz;
use Revobot\Revobot;

class AnswerCmd extends BaseCmd
{
    private Revobot $bot;

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
        $this->setDescription('/answer ответ');
    }

    public function exec(): string
    {
        if (empty($this->input)) {
            return $this->description;
        }
        $quiz = (new Quiz($this->bot));

        $quiz->sendAnswer($this->input);
        return '';
    }
}