<?php

namespace Revobot\Commands;

use Revobot\Services\Znanija;

class ZnCmd extends BaseCmd
{
    const KEYS = ['zn', 'znanija', 'зн', 'знания'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'получить ответы со znanija';

    public function __construct(string $input)
    {
        parent::__construct($input);
        $this->setDescription('/zn введите вопрос');
    }

    public function exec(): string
    {
        if (empty($this->input)) {
            return $this->description;
        }
        return Znanija::get($this->input);
    }
}
