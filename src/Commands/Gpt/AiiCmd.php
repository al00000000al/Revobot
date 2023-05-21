<?php

namespace Revobot\Commands\Gpt;

use Revobot\Commands\BaseCmd;
use Revobot\Games\AI\Gpt;
use Revobot\Revobot;

class AiiCmd extends BaseCmd
{
    private Revobot $bot;

    public const KEYS = ['aii','иии'];
    public const IS_ENABLED = true;
    public const HELP_DESCRIPTION = 'Очистить контекст и историю и ответить';


    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
        $this->setDescription("Введите /aii текст");
    }

    public function exec(): string
    {
        if (!empty($this->input)) {
            return Gpt::generate($this->input, $this->bot->pmc, $this->bot->getUserId(), $this->bot->provider, true);
        }
        return $this->description;
    }

}
