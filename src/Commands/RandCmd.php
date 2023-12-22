<?php

namespace Revobot\Commands;

class RandCmd extends BaseCmd
{

    const KEYS = ['rand', 'random', 'ранд', 'рандом'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'Случайное число';


    public function __construct($input)
    {
        parent::__construct($input);
        $this->setDescription('Введите /rand min max');
    }

    /**
     * @return string
     */
    public function exec(): string
    {
        if (!empty($this->input)) {
            list($min, $max) = explode(' ', $this->input);

            if ((int)$min > (int)$max) {
                return $this->description;
            }
        } else {
            $min = 0;
            $max = 100;
        }

        return "Ваше число: " . mt_rand((int)$min, (int)$max);
    }
}
