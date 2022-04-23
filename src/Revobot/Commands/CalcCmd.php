<?php

namespace Revobot\Commands;

use Math\Parser;

class CalcCmd extends BaseCmd
{
    protected string $description = 'Введите /calc [выражение]';

    public function exec(): string
    {
        if(empty($this->input)){
            return $this->description;
        }
        $this->input = str_replace(['+','-','/','%','*'], [' + ',' - ',' / ',' % ',' * '], $this->input);
        return (new  Parser())->evaluate($this->input);
    }
}
