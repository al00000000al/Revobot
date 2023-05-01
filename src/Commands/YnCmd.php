<?php

namespace Revobot\Commands;

use Revobot\Games\Predictor\Utils;
use Revobot\Games\Predictor\YesNo;
use Revobot\Revobot;
use Revobot\Services\OpenAIService;

class YnCmd extends BaseCmd
{

    const KEYS = ['yn','дн'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'Да или нет';
    private Revobot $bot;


    public function __construct($input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
        $this->setDescription('Введите /yn <событие>');
    }

    /**
     * @return string
     */
    public function exec(): string
    {
        if (empty($this->input)) {
            return $this->description;
        }

        $input = Utils::replaceDate($this->input);
        $input = Utils::replaceMe($this->bot->getUserId(), $input);

        if (USE_AI_CMD) {
            return OpenAIService::generate($input, "На все вопросы и предложения отвечай только да или нет. Если нельзя ответить или оскорбительно, то пиши не знаю", []);
        }
        return (new YesNo($input))->calc();
    }


}
