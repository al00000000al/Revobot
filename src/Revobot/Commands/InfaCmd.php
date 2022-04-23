<?php

namespace Revobot\Commands;

use Revobot\Games\Predictor\Percents;

class InfaCmd extends BaseCmd
{
    protected string $description = 'Введите /infa <событие>';

    public function exec(): string
    {
        if (empty($this->input)) {
            return $this->description;
        }

        return (new Percents($this->input))->calc();
    }

}