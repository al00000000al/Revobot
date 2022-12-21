<?php

namespace Revobot\Commands;

class EchoCmd extends BaseCmd
{

    const KEYS = ['echo','эхо','excho','print','принт'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'Печатать';

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
