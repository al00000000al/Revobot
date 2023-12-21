<?php

namespace Revobot\Commands;

use Revobot\Revobot;
use Revobot\Util\PMC;
use Revobot\Util\Strings;

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

        list($key, $data) = Strings::parseSubCommand($this->input);
        $pmc_key = self::getKey($this->bot->provider, $this->bot->getUserId(), $key);

        if (empty($data)) {
            PMC::delete($pmc_key);
            return 'Данные удалены';
        }

        PMC::set($pmc_key, substr($data, 0, 4096));
        return 'Данные сохранены';
    }

    private function getKey($provider, $user_id, $key)
    {
        return 'storage_' . $provider . '_' . $user_id . $key;
    }
}
