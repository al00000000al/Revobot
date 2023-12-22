<?php

namespace Revobot\Commands;

class DonateCmd extends BaseCmd
{
    const KEYS = ['donate', 'донат'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'Купить ревокоины';

    public function __construct(string $input)
    {
        parent::__construct($input);
    }

    public function exec(): string
    {
        return "";
    }
}
