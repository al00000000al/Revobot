<?php

namespace Revobot\Commands;

use Revobot\Services\SberGPT3;

class SberCmd extends BaseCmd
{
    const KEYS = ['sber', 'сбер'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'sber ai';

    public function __construct(string $input)
    {
        parent::__construct($input);
        $this->setDescription('/sber введите текст');
    }

    public function exec(): string
    {
        if (empty($this->input)) {
            return $this->description;
        }
        return SberGPT3::generate($this->input);
    }
}
