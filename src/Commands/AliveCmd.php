<?php

namespace Revobot\Commands;

class AliveCmd extends BaseCmd
{



    /**
     * @return string
     */
    public function exec(): string
    {
        return 'Жив!';
    }
}
