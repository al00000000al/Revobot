<?php

namespace Revobot\Commands;


class IdCmd extends BaseCmd
{
    const KEYS = ['id', 'ид'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'Мой ид';

    public function __construct(string $input)
    {
        parent::__construct($input);
    }

    public function exec(): string
    {
        return (string)userId();
    }
}
