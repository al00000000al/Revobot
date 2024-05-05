<?php

namespace Revobot\Commands;

use Revobot\Services\DobroAI;

class PorfirevichCmd extends BaseCmd
{
    const KEYS = ['porfirevich', 'porf', 'порф', 'порфирьевич'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'порфирьевич ии';

    public function __construct(string $input)
    {
        parent::__construct($input);
        $this->setDescription('/porfirevich введите текст');
    }

    public function exec(): string
    {
        if (empty($this->input)) {
            return $this->description;
        }
        return DobroAI::get($this->input);
    }
}
