<?php

namespace Revobot\Commands\Gpt;

use Revobot\Games\AI\Gpt;
use Revobot\Revobot;

class AiCmd extends BaseCmd
{
    private Revobot $bot;

    public const KEYS = ['ai','ии'];
    public const IS_ENABLED = true;
    public const HELP_DESCRIPTION = 'Нейросеть';


    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
        $this->setDescription("Введите /ai текст");
    }

    public function exec(): string
    {
        if (!empty($this->input)) {
            return Gpt::generate($this->input, $this->bot->pmc, $this->bot->getUserId(), $this->bot->provider);
        }
        return $this->description;
    }

}
