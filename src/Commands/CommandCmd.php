<?php

namespace Revobot\Commands;

class CommandCmd extends BaseCmd
{
    public function __construct(string $input)
    {
        parent::__construct($input);
        $this->setDescription('/command <команда> <текст или команда>');
    }
}