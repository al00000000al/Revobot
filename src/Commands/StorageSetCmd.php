<?php

namespace Revobot\Commands;

use Revobot\Revobot;
use Revobot\Util\PMC;

class StorageSetCmd extends BaseCmd
{
    const KEYS = ['storageset'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'Сохранить значение в ключе';
    private Revobot $bot;

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->setDescription('Введите /storageset ключ строка');
        $this->bot = $bot;
    }

    public function exec(): string
    {
        if (empty($this->input)) {
            return $this->description;
        }

        $parts = explode(' ', $this->input);
        $key = $parts[0];
        $data = implode(' ', array_slice($parts, 1));

        PMC::set(self::getKey($this->bot->provider, $this->bot->getUserId(), $key), substr($data, 0, 4096));
        return 'Данные сохранены';
    }

    private function getKey($provider, $user_id, $key)
    {
        return 'storage_' . $provider . '_' . $user_id . $key;
    }
}
