<?php

namespace Revobot\Commands\Custom;

use Revobot\Commands\EchoCmd;
use Revobot\Commands\ExecuteCmd;
use Revobot\CommandsManager;
use Revobot\Config;
use Revobot\Money\Revocoin;
use Revobot\Revobot;
use Revobot\Services\Providers\Tg;
use Revobot\Util\PMC;

class CustomCmd
{
    private Revobot $bot;
    private int $user_id;

    public const PMC_COMMAND_KEY = 'custom_cmd_'; //.$cmd_name;
    public const PMC_USER_COMMANDS_KEY = 'custom_user_cmd_'; //.$provider.'_'.$user_id;

    public function __construct(Revobot $bot)
    {
        $this->bot = $bot;
        $this->user_id = $this->bot->getUserId();
    }


    /**
     * @param int $type
     * @return bool
     */
    public function hasMoney(int $type): bool
    {
        $user_balance = (new Revocoin($this->bot))->getBalance($this->user_id);


        if ($type === Types::TYPE_ALIAS) {
            $cmd_cost = Prices::PRICE_ALIAS;
        } elseif ($type === Types::TYPE_TEXT) {
            $cmd_cost = Prices::PRICE_TEXT;
        } elseif ($type === Types::TYPE_CODE) {
            $cmd_cost = Prices::PRICE_CODE;
        } else {
            return false;
        }
        if ($user_balance <= $cmd_cost) {
            return false;
        }

        return true;
    }

    /**
     * @param string $command_name
     * @return bool
     */
    public function isExistCmd(string $command_name): bool
    {
        return in_array($command_name, CommandsManager::COMMANDS, true);
    }

    /**
     * @param string $command_name
     * @return bool
     */
    public function isExistCustomCmd(string $command_name): bool
    {
        if (!empty($this->getCustomCmd($command_name))) {
            return true;
        }
        return false;
    }

    /**
     * @param string $command_name
     * @return bool
     */
    public function isExistCustomCmdCode(string $command_name): bool
    {
        $cmd = $this->getCustomCmd($command_name);
        if (isset($cmd['command_type']) && $cmd['command_type'] === Types::TYPE_CODE) {
            return true;
        }
        return false;
    }

    /**
     * @param string $command_name
     * @return mixed
     */
    public function getCustomCmd(string $command_name): array
    {
        $result = PMC::get(self::PMC_COMMAND_KEY . sha1($command_name));
        if (empty($result)) {
            return [];
        }
        return $result;
    }


    /**
     * @param string $command_name
     * @return bool
     */
    public function isValidCommand(string $command_name): bool
    {
        $message = '/' . $command_name . ' test';
        list($command, $_) = CommandsManager::extract($message);
        if (!empty($command)) {
            return true;
        }
        return false;
    }

    /**
     * @param int $user_id
     * @return mixed
     */
    public function getUserCommands(int $user_id, string $provider = 'tg'): array
    {
        $result = PMC::get(self::PMC_USER_COMMANDS_KEY . $provider . '_' . $user_id);
        if (empty($result)) {
            return [];
        }
        return $result;
    }


    /**
     * @param int $user_id
     * @param string $command_name
     * @param int $command_type
     * @param array $args
     */
    public function addCommand(int $user_id, string $command_name, int $command_type, array $args, string $provider = 'tg')
    {
        $user_commands = $this->getUserCommands($user_id);
        $user_commands[] = $command_name;


        $command = [
            'user_id' => $user_id,
            'command_name' => $command_name,
            'command_type' => $command_type,
            'args' => $args,
            'created_at' => time(),
        ];

        PMC::set(self::PMC_COMMAND_KEY . sha1($command_name), $command);
        PMC::set(self::PMC_USER_COMMANDS_KEY . $provider . '_' . $user_id, $user_commands);
    }

    /**
     * @param int $user_id
     * @param string $command_name
     */
    public function deleteCommand(int $user_id, string $command_name, string $provider = 'tg')
    {
        PMC::delete(self::PMC_COMMAND_KEY . sha1($command_name));
        $user_commands = array_diff($this->getUserCommands($user_id), [$command_name]);
        PMC::set(self::PMC_USER_COMMANDS_KEY . $provider . '_' . $user_id, $user_commands);
    }


    /**
     * @return string
     */
    public function run($round = 0): string
    {
        list($command, $input) = CommandsManager::extract($this->bot->message);
        $custom_cmd = $this->getCustomCmd($command);

        if ($custom_cmd && isset($custom_cmd['command_type'])) {
            switch ((int)($custom_cmd['command_type'])) {
                case Types::TYPE_ALIAS:
                    $command_name = (string)$custom_cmd['args'][0];
                    $result = (string) CommandsManager::run($this->bot, $command_name, $input);
                    if (empty($result)) {
                        // Возможно это alias alias
                        if ($round < 3) {
                            $new_message = str_replace('@Therevoluciabot', '', $this->bot->message);
                            $new_message = str_replace('/' . $command, '/' . $command_name, $this->bot->message);
                            $this->bot->setMessage($new_message);
                            return $this->run($round + 1);
                        } else {
                            return '';
                        }
                    } else {
                        return $result;
                    }
                case Types::TYPE_TEXT:
                    $string = (string)$custom_cmd['args'][0];
                    return (new EchoCmd($string))->exec();
                case Types::TYPE_CODE:
                    $data = json_decode((string)$custom_cmd['args'][0], true);
                    $code = trim((string)$data['code']);
                    // Tg::sendMessage((int)Config::getArr('tg_bot_admins')[0], $code);
                    global $BotMessage;
                    $BotMessage = $this->bot->message;
                    if (isset($this->bot->raw_data['reply_to_message'])) {
                        $BotMessage = (string)$this->bot->raw_data['reply_to_message']['text'];
                    }
                    if (!empty($BotMessage)) {
                        $arr = explode(' ', $BotMessage);
                        array_shift($arr);
                        if (!empty($BotMessage)) {
                            $BotMessage = implode(' ', $arr);
                        }
                    }


                    return (new ExecuteCmd($code, $this->bot))->exec();
            }
        }
        return '';
    }
}
