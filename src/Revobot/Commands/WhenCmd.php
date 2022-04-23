<?php

namespace Revobot\Commands;

use Revobot\Games\Predictor\When;

class WhenCmd extends BaseCmd
{
    protected string $description = 'Введите /when событие';

    public function exec(): string
    {
        if (!empty($this->input)) {
            return (new When($this->input))->calc();
        }
        return $this->description;
    }
}