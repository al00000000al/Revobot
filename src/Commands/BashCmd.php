<?php

namespace Revobot\Commands;

use Revobot\Services\Bash;

class BashCmd extends BaseCmd
{
    /**
     * @return string
     */
    public function exec(): string
    {
        return Bash::get();
    }
}
