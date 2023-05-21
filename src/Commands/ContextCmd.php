<?php

namespace Revobot\Commands;

use Revobot\Games\AI\GptPMC;
use Revobot\Revobot;

class ContextCmd extends BaseCmd
{
    private Revobot $bot;
    public const KEYS = ['context','контекст'];
    public const IS_ENABLED = true;
    public const HELP_DESCRIPTION = 'Установить контекст нейросети';

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
        $this->setDescription("Введите /context текст");
    }

    public function exec(): string
    {
        $GptPMC = new GptPMC($this->bot->pmc, $this->bot->getUserId(), $this->bot->provider);

        if (!empty($this->input)) {
            $GptPMC->setContext($this->input);
            return "Значение изменено";
        }
        return $GptPMC->getContext();
    }

}
