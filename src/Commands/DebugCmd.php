<?php

namespace Revobot\Commands;

use Revobot\Util\PMC;

class DebugCmd extends BaseCmd
{
    const KEYS = ['debug'];
    const IS_ENABLED = true;
    const IS_ADMIN_ONLY = true;
    const HELP_DESCRIPTION = 'toggle debug (admin)';

    public function __construct(string $input)
    {
        parent::__construct($input);
        $this->setDescription('/debug toggle debug');
    }

    public function exec(): string
    {
        if (!$this->isAdmin(userId())) {
            return '';
        }

        $is_debug = (bool) PMC::get('debug');
        $is_debug = !$is_debug;
        PMC::set('debug', $is_debug);
        return 'Set debug to:' . ((int)$is_debug);
    }
}
