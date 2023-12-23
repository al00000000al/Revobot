<?php

namespace Revobot\Commands;

use Revobot\Commands\Custom\CustomCmd;
use Revobot\Commands\Custom\Prices;
use Revobot\Commands\Custom\Types;
use Revobot\Money\Revocoin;
use Revobot\Revobot;
use Revobot\Util\Strings;

class EditCodeCmd extends BaseCmd
{
    private Revobot $bot;
    const KEYS = ['editcode', 'редактироватькод'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'Редактировать команду с кодом lua (0R)';

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->setDescription('/editcode команда код');
        $this->bot = $bot;
    }

    public function exec(): string
    {

        if (empty($this->input)) {
            return $this->description;
        }
        $customCmd = new CustomCmd($this->bot);
        list($command_name, $text) = Strings::parseSubCommand($this->input);

        $data = explode('|', $text, 2);
        if (count($data) > 1) {
            $params = ($data[0]);
            $text = $data[1];
        } else {
            $params = '';
        }
        $user_id = $this->bot->getUserId();
        $user_commands = $customCmd->getUserCommands($user_id);
        if (!in_array($command_name, $user_commands, true)) {
            return 'Вы не можете редактировать эту команду или такой нет';
        }

        if (!$customCmd->isValidCommand($command_name) ||  empty($command_name)) {
            return 'Недопустимое имя';
        }

        if ($customCmd->isExistCmd($command_name) || !$customCmd->isExistCustomCmdCode($command_name)) {
            return 'Эту нельзя редактировать';
        }

        $user_id = $this->bot->getUserId();
        $customCmd->deleteCommand($user_id, $this->input);
        $customCmd->addCommand($user_id, $command_name, Types::TYPE_CODE, [json_encode(['code' => $text])]);

        return 'Команда /' . $command_name . ' изменена! ';
    }
}
