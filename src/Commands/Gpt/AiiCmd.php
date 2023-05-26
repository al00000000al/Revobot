<?php

namespace Revobot\Commands\Gpt;

use Revobot\Commands\BaseCmd;
use Revobot\Games\AI\Gpt;
use Revobot\JobWorkers\JobLauncher;
use Revobot\JobWorkers\Requests\Gpt as RequestsGpt;
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
            $this->bot->sendTypeStatusTg();

            if (!JobLauncher::isEnabled()) {
                return Gpt::generate($this->input, $this->bot->getUserId(), $this->bot->provider, true);
            }

            $job_request = new RequestsGpt([
                'input' => $this->input,
                'user_id' => $this->bot->getUserId(),
                'provider' => $this->bot->provider,
                'chat_id' => $this->bot->chat_id,
                'clear_all' => true
              ]);
            JobLauncher::start($job_request, 120);
            return "";
        }
        return $this->description;
    }

}
