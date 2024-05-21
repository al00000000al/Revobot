<?php

namespace Revobot\Commands;

use Revobot\Commands\Custom\CustomCmd;
use Revobot\Money\Revocoin;
use Revobot\Revobot;
use Revobot\Util\PMC;

class DefecatorCmd extends BaseCmd
{
    private Revobot $bot;
    const KEYS = ['defecator', 'дефекатор'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'Рандомное выполнение команд в чате';
    public const PMC_DEFCMD_KEY = 'defcmd_key_'; // provider() . '_'.chatid();

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
    }

    public function exec(): string
    {
        $commands = $this->getCommands();
        $user_cmd = trim($this->input);
        if (empty($user_cmd)) {
            $outputText = '';
            if (!empty($commands) && count($commands) > 0) {
                sort($commands);
                foreach ($commands as $command) {
                    $outputText .= '/' . $command . "\n";
                }
                $outputText = "Рандомные команды в чате:\n{$outputText}";
            } else {
                $outputText = 'В чате нет комманд';
            }
            return $outputText . "\nДобавить команду (100 R):\n/defecator cmd_name\n\nУдалить команду (бесплатно):\n/defecator cmd_name";
        }
        if (in_array($user_cmd, $commands)) {
            // удаление
            $key = array_search($user_cmd, $commands);
            if ($key !== false) {
                unset($commands[$key]);
            }
            $commands = array_values($commands);
            $this->setCommands($commands);
            return 'Команда удалена из списука';
        } else {
            $customCmd = new CustomCmd($this->bot);
            if (!$customCmd->isExistCmd($user_cmd) && !$customCmd->isExistCustomCmd($user_cmd)) {
                return 'Такой команды нет, поэтому ее нельзя добавить';
            }
            // добавление
            $result = (new Revocoin($this->bot))->transaction(100.0, $this->bot->getBotId(), userId());
            if ($result) {

                $commands[] = $user_cmd;
                $this->setCommands($commands);
                return 'Комманда добавлена в список. С вас 100 AED';
            } else {
                return 'У вас недостаточно денек';
            }
        }

        return 'Хрень какаято опять';
    }

    public static function getKey(): string
    {
        return self::PMC_DEFCMD_KEY . provider() . '_' . chatId();
    }

    private function getCommands(): array
    {
        return (array)PMC::get(self::getKey());
    }

    private function setCommands(array $commands)
    {
        PMC::set(self::getKey(), $commands);
    }
}
