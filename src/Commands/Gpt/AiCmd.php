<?php

namespace Revobot\Commands\Gpt;

use Revobot\Commands\BaseCmd;
use Revobot\Config;
use Revobot\Games\AI\Gpt;
use Revobot\Games\AI\GptPMC;
use Revobot\JobWorkers\JobLauncher;
use Revobot\JobWorkers\Requests\Gpt as RequestsGpt;
use Revobot\Revobot;

class AiCmd extends BaseCmd
{
    private Revobot $bot;

    public const KEYS = ['ai', 'ии'];
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
            $base_path = Config::get('base_path');
            $GptPMC = new GptPMC(userId(), $this->bot->provider);
            $save_history = 1;
            $chat_id = chatId();
            $user_id = userId();
            $message_id = $this->bot->raw_data['message_id'];
            $input = $this->input;
            $GptPMC->setInput($input);
            exec("cd {$base_path}/scripts && php gptd.php $user_id $save_history $chat_id $message_id > /dev/null 2>&1 &");

            // if (!JobLauncher::isEnabled()) {
            //     return Gpt::generate($this->input, userId(), $this->bot->provider, false, 'gpt-3.5-turbo');
            //   }

            //   $job_request = new RequestsGpt([
            //     'input' => $this->input,
            //     'user_id' => userId(),
            //     'provider' => $this->bot->provider,
            //     'chat_id' =>chatId(),
            //     'model' => 'gpt-3.5-turbo'
            //   ]);
            // JobLauncher::start($job_request, 120);
            // return "";
        }
        return '';
    }
}
