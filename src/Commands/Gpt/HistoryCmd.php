<?php

namespace Revobot\Commands\Gpt;

use Revobot\Commands\BaseCmd;
use Revobot\Config;
use Revobot\Games\AI\GptPMC;
use Revobot\Revobot;
use Revobot\Services\Providers\Tg;

class HistoryCmd extends BaseCmd
{
    private Revobot $bot;
    public const KEYS = ['history','h', 'ист', 'история'];
    public const IS_ENABLED = true;
    public const HELP_DESCRIPTION = 'История';

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
    }

    public function exec(): string
    {
        $base_path = Config::get('base_path');
        $chat_id = (int)$this->bot->chat_id;
        $user_id = (int)$this->bot->getUserId();
        exec("cd {$base_path} && php tg_sendfile.php $chat_id $user_id");
        return '';
    }

}
