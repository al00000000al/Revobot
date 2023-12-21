<?php

namespace Revobot\Commands;

use Revobot\Revobot;
use Revobot\Util\PMC;

class StorageGetCmd extends BaseCmd
{
    const KEYS = ['storageget'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'Получить значение из ключа';
    private Revobot $bot;

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->setDescription('Введите /storageget ключ');
        $this->bot = $bot;
    }

    public function exec(): string
    {
        if (empty($this->input)) {
            return $this->description;
        }

        return (string) PMC::get(self::getKey($this->bot->provider, $this->bot->getUserId(), $this->input));
    }

    private function getKey($provider, $user_id, $key)
    {
        return 'storage_' . $provider . '_' . $user_id . $key;
    }
}
