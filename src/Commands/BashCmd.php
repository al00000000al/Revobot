<?php

namespace Revobot\Commands;

use Revobot\Services\Bash;

class BashCmd extends BaseCmd
{

    const KEYS = ['bash', 'баш'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'Случайная шутка';

    /**
     * @return string
     */
    public function exec(): string
    {
        return Bash::get();
    }
}
