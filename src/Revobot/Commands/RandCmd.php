<?php

namespace Revobot\Commands;

use Revobot\Games\Predictor\Percents;

class RandCmd extends BaseCmd
{
    protected string $description = 'Введите /rand min max';

    public function exec(): string
    {
        if (!empty($this->input)) {
            list($min, $max) = explode(' ', $this->input);

            if ($min == null || $max == null || $min > $max) {
                return $this->description;
            }
        } else {
            $min = 0;
            $max = 100;
        }

        return "Ваше число: " . mt_rand($min, $max);
    }
}