<?php

namespace Revobot\Commands;

use Revobot\Games\Predictor\Percents;

class InfaCmd extends BaseCmd
{

    /**
     * @param $input
     */
    public function __construct($input)
    {
        parent::__construct($input);
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

        return (new Percents($this->input))->calc();
    }

}