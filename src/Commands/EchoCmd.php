<?php

namespace Revobot\Commands;

class EchoCmd extends BaseCmd
{

    public function __construct(string $input)
    {
        parent::__construct($input);
        $this->setDescription('/echo текст для вывода');
    }

    public function exec(): string
    {
        if (!$this->input){
            return $this->description;
        }
        return $this->input;
    }
}
