<?php

namespace Revobot\Commands;

use Revobot\Config;
use Revobot\Games\Predictor\When;
use Revobot\Services\OpenAIService;

class WhenCmd extends BaseCmd
{
    const KEYS = ['when', 'kogda', 'когда'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'Узнать когда';

    /**
     * @param string $input
     */
    public function __construct($input)
    {
        parent::__construct($input);
        $this->setDescription('Введите /when событие');
    }

    /**
     * @return string
     */
    public function exec(): string
    {
        if (!empty($this->input)) {
            if ((bool) Config::getInt('use_ai_cmd')) {
                list($_, $answer) = OpenAIService::generate($this->input, "На все вопросы отвечай только датой и временем когда это событие может произойти или уже произошло, если запрос неприемлем, то пиши случайную дату", []);
                return (string)$answer;
            }
            return (new When($this->input))->calc();
        }
        return $this->description;
    }
}
