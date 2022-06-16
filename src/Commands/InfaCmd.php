<?php

namespace Revobot\Commands;

use Revobot\Games\Predictor\Percents;
use Revobot\Games\Predictor\Utils;
use Revobot\Revobot;

class InfaCmd extends BaseCmd
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
        $this->setDescription('Введите /infa <событие>');
    }

    /**
     * @return string
     */
    public function exec(): string
    {
        if (empty($this->input)) {
            return $this->description;
        }

        $input = Utils::replaceDate($this->input);
        $input = Utils::replaceMe($this->bot->getUserId(), $input);

        return (new Percents($input))->calc();
    }

}