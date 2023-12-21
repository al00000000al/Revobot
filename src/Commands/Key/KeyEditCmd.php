<?php

namespace Revobot\Commands\Key;

use Revobot\Commands\BaseCmd;
use Revobot\Revobot;
use Revobot\Util\PMC;
use Revobot\Util\Strings;

class KeyEditCmd extends BaseCmd
{
    private Revobot $bot;
    const KEYS = ['key.edit'];
    const IS_ENABLED = true;
    const IS_ADMIN_ONLY = true;
    const HELP_DESCRIPTION = 'key edit';


    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
        $this->setDescription('Введите /key.edit <ключ> <значение>');
    }

    public function exec(): string
    {
        if (!$this->isAdmin($this->bot->getUserId())) {
            return '';
        }

        if (!empty($this->input)) {
            list($key, $value) = Strings::parseSubCommand($this->input);
            if (!empty($value)) {
                PMC::set($key, $value);
                return 'Ключ ' . $key . '=' . $value;
            }
        }
        return $this->description;
    }
}
