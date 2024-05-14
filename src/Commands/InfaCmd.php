<?php

namespace Revobot\Commands;

use Revobot\Config;
use Revobot\Games\Predictor\Percents;
use Revobot\Games\Predictor\Utils;
use Revobot\Revobot;
use Revobot\Services\OpenAIService;

class InfaCmd extends BaseCmd
{

    const KEYS = ['infa', 'инфа'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'Вероятность события';

    private Revobot $bot;


    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
        $this->setDescription('Введите /infa <событие>');
    }

    /**
     * @return string
     */
    public function exec(): string
    {
        if (empty($this->input)) {
            return $this->description;
        }

        if ((bool) Config::getInt('use_ai_cmd')) {
            $this->bot->sendTypeStatus();
            list($_, $answer) = OpenAIService::generate($this->input, "На любой текст или фразу ты представляешь вероятность этого события в цифрах, если это не представляется возможным или запрос неадекватный тогда пиши 0%. Нужно только число", []);
            return (string)$answer;
        } else {
            $input = Utils::replaceDate($this->input);
            $input = Utils::replaceMe(userId(), $input);

            return (new Percents($input))->calc();
        }
    }
}
