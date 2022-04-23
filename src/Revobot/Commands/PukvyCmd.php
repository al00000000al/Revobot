<?php

namespace Revobot\Commands;

use Revobot\Games\Pukvy;

class PukvyCmd extends BaseCmd
{

    protected string $description = 'Введите /pukvy два слова';

    public function exec(): string
    {

        if (mb_strlen($this->input) > 0) {
            $words = explode(' ', $this->input);
            if (count($words) < 2 || count($words) > 2) {
                return $this->description;
            }

            return (new Pukvy($this->input))->convert();

        }else{
            return $this->description;
        }


    }
}