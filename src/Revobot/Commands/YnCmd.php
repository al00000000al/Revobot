<?php

namespace Revobot\Commands;

use Revobot\Games\Predictor\YesNo;

class YnCmd extends BaseCmd
{
    protected string $description = 'Введите /yn <событие>';

    public function exec(): string
    {
        if (empty($this->input)) {
            return $this->description;
        }

        return (new YesNo($this->input))->calc();
    }


}