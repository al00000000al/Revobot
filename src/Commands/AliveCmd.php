<?php

namespace Revobot\Commands;

use Revobot\Util\PMC;

class AliveCmd extends BaseCmd
{

    const KEYS = ['alive', 'алив'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'Состояние бота';

    public function __construct(string $input)
    {
        parent::__construct($input);
    }

    /**
     * @return string
     */
    public function exec(): string
    {
        $pmc_v = PMC::getVersion();
        return "Жив! PMC: $pmc_v, Bot build: 181";
    }
}
