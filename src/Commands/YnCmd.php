<?php

namespace Revobot\Commands;

use Revobot\Games\Predictor\YesNo;

class YnCmd extends BaseCmd
{

    /**
     * @param $input
     */
    public function __construct($input)
    {
        parent::__construct($input);
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

        return (new YesNo($this->input))->calc();
    }


}