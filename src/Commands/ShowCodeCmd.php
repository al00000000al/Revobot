<?php

namespace Revobot\Commands;

use Revobot\Commands\Custom\CustomCmd;
use Revobot\Commands\Custom\Types;
use Revobot\Revobot;

class ShowCodeCmd extends BaseCmd
{
    const KEYS = ['showcode'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'show code';
    private Revobot $bot;

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->setDescription('/showcode команда');
        $this->bot = $bot;
    }

    public function exec(): string
    {
        if (empty($this->input)) {
            return $this->description;
        }
        $user_id = $this->bot->getUserId();
        $customCmd = new CustomCmd($this->bot);
        $command_name = $this->input;
        $user_commands = $customCmd->getUserCommands($user_id);

        if (!in_array($command_name, $user_commands, true)) {
            return 'Вы не можете просматривать эту команду или такой нет';
        }

        $cmd = $customCmd->getCustomCmd($command_name);

        if (isset($cmd['command_type']) && $cmd['command_type'] === Types::TYPE_CODE) {
            $data = json_decode((string)$cmd['args'][0], true);
            $code = trim((string)$data['code']);
            return $code;
        } else {
            return 'Это не код';
        }
        return '';
    }
}
