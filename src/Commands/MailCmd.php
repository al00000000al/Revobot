<?php

namespace Revobot\Commands;

use Revobot\Services\AnswersMailru;

class MailCmd extends BaseCmd
{
    const KEYS = ['mail', 'меил'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'get answer from otveti mail ru';

    public function __construct(string $input)
    {
        parent::__construct($input);
        $this->setDescription('/mail введи вопрос');
    }

    public function exec(): string
    {
        if (empty($this->input)) {
            return $this->description;
        }
        $answer =  AnswersMailru::get($this->input);
        if (empty($answer)) {
            return 'Ничего не ношли :(';
        }
        return $answer;
    }
}
