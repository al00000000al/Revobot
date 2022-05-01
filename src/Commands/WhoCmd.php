<?php

namespace Revobot\Commands;

use Revobot\Games\Predictor\Who;
use Revobot\Revobot;

class WhoCmd extends BaseCmd
{

    private Revobot $bot;

    /**
     * @param $input
     * @param Revobot $bot
     */
    public function __construct($input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
        $this->setDescription('Введите /who событие');
    }

    /**
     * @return string
     */
    public function exec(): string
    {
        if (!empty($this->input)) {
            return (new Who($this->input, $this->bot))->calc();
        }
        return $this->description;
    }
}
