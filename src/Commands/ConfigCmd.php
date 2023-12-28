<?php

namespace Revobot\Commands;

use Revobot\Revobot;
use Revobot\Util\PMC;

class ConfigCmd extends BaseCmd
{

    const KEYS = ['config', 'конфиг'];
    const IS_ENABLED = true;
    const IS_ADMIN_ONLY = true;
    const HELP_DESCRIPTION = 'Конфиг';

    public function __construct(string $input)
    {
        parent::__construct($input);
        $this->setDescription('Введите /config <ключ>');
    }

    public function exec(): string
    {
        if (!$this->isAdmin(userId())) {
            return '';
        }

        if (!empty($this->input)) {
            return print_r($this->getKey($this->input), true);
        }
        return $this->description;
    }

    private function getKey($key)
    {
        return PMC::get($key);
    }
}
