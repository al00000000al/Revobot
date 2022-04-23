<?php

namespace Revobot\Commands;

use Revobot\Services\Bash;

class BashCmd extends BaseCmd
{
    public function exec(): string
    {
        return Bash::get();
    }
}
