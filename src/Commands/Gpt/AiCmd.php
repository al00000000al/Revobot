<?php

namespace Revobot\Commands\Gpt;

use Revobot\Commands\BaseCmd;
use Revobot\Games\AI\Gpt;
use Revobot\Games\AI\GptPMC;
use Revobot\JobWorkers\JobLauncher;
use Revobot\JobWorkers\Requests\Gpt as RequestsGpt;
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
            $GptPMC = new GptPMC($this->bot->getUserId(), $this->bot->provider);
            $save_history = 1;
            $chat_id = (int)$this->bot->chat_id;
            $user_id = (int)$this->bot->getUserId();
            $input = $this->input;
            $GptPMC->setInput($input);
            exec("php /home/opc/www/revobot/gptd.php $user_id $save_history $chat_id > /dev/null 2>&1 &");

            // if (!JobLauncher::isEnabled()) {
            //     return Gpt::generate($this->input, $this->bot->getUserId(), $this->bot->provider, false, 'gpt-3.5-turbo');
            //   }

            //   $job_request = new RequestsGpt([
            //     'input' => $this->input,
            //     'user_id' => $this->bot->getUserId(),
            //     'provider' => $this->bot->provider,
            //     'chat_id' => $this->bot->chat_id,
            //     'model' => 'gpt-3.5-turbo'
            //   ]);
            // JobLauncher::start($job_request, 120);
            // return "";
        }
        return '';
    }

}
