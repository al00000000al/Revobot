<?php

namespace Revobot\Commands;

class AliasCmd extends BaseCmd
{
    public function __construct(string $input)
    {
        parent::__construct($input);
        $this->setDescription('/alias <команда> <новое название>');
    }
}