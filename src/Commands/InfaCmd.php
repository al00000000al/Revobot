<?php

namespace Revobot\Commands;

use Revobot\Games\Predictor\Percents;
use Revobot\Games\Predictor\Utils;
use Revobot\Revobot;

class InfaCmd extends BaseCmd
{

    const KEYS = ['infa','инфа'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'Вероятность события';

    private Revobot $bot;


    public function __construct(string $input, Revobot $bot)
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
