<?php

namespace Revobot\Commands;

use Revobot\Games\Predictor\Utils;
use Revobot\Games\Predictor\YesNo;
use Revobot\Revobot;

class YnCmd extends BaseCmd
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
        $this->setDescription('Введите /yn <событие>');
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
        return (new YesNo($input))->calc();
    }


}