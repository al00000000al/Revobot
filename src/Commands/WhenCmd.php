<?php

namespace Revobot\Commands;

use Revobot\Games\Predictor\When;

class WhenCmd extends BaseCmd
{

    /**
     * @param $input
     */
    public function __construct($input)
    {
        parent::__construct($input);
        $this->setDescription('Введите /when событие');
    }

    /**
     * @return string
     */
    public function exec(): string
    {
        if (!empty($this->input)) {
            return (new When($this->input))->calc();
        }
        return $this->description;
    }
}