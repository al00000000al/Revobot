<?php

namespace Revobot\Commands\Gpt;

use Revobot\Commands\BaseCmd;
use Revobot\Games\AI\Gpt;
use Revobot\Games\AI\GptPMC;
use Revobot\JobWorkers\JobLauncher;
use Revobot\JobWorkers\Requests\Gpt as RequestsGpt;
use Revobot\Neural\Answers;
use Revobot\Revobot;
use Revobot\Services\AnswersMailru;
use Revobot\Services\DobroAI;

class AiiCmd extends BaseCmd
{
    private Revobot $bot;

    public const KEYS = ['aii', 'иии'];
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
        if (empty($this->input)) {
            return $this->description;
        }
        $answer =  AnswersMailru::get($this->input);
        if (empty($answer)) {
            $answer = DobroAI::get("- " . $this->input . "\r\n- ");
        }
        (new ClearAllCmd($this->input, $this->bot))->exec();
        return (new AiCmd($this->input, $this->bot))->exec();
    }
}
