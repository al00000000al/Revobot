<?php

namespace Revobot\Commands;

use Revobot\Games\Quiz;
use Revobot\Revobot;

class AnswerCmd extends BaseCmd
{
    private Revobot $bot;

    const KEYS = ['answer','ответ'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'Ответить на вопрос';

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
        $this->setDescription('/answer буква ответа');
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
