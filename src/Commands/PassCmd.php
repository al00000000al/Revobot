<?php

namespace Revobot\Commands;

use Revobot\Commands\Custom\CustomCmd;
use Revobot\Games\Predictor\Utils;
use Revobot\Games\Predictor\YesNo;
use Revobot\Games\Todo;
use Revobot\Revobot;

class PassCmd extends BaseCmd
{

    const KEYS = ['pass','пароль'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'Пароль для переноса данных';
    private Revobot $bot;

    /**
     * @param $input
     * @param Revobot $bot
     */
    public function __construct($input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
        $this->setDescription('Введите /pass');
    }

    /**
     * @return string
     */
    public function exec(): string
    {
        if((int)$this->bot->chat_id !== (int)$this->bot->getUserId()){
            return "Эта комманда работает только в личном чате с ботом";
        }
        return "не сегодня";
    }

    public function transfer(int $old_user_id, int $user_id, string $provider = 'tg'){
        self::transferUserCommands($old_user_id, $user_id, $provider);
        self::transferUserTodos($old_user_id, $user_id, $provider);
    }

    private function transferUserCommands(int $old_user_id, int $user_id, string $provider = 'tg'){
        $myCustomCmd = new CustomCmd($this->bot);
        $commands = $myCustomCmd->getUserCommands($old_user_id, $provider);
        foreach($commands as $cmd){
            $cmd_data = $myCustomCmd->getCustomCmd($cmd);
            $myCustomCmd->deleteCommand($old_user_id, $cmd, $provider);
            $myCustomCmd->addCommand($user_id, $cmd, $cmd_data['command_type'], $cmd_data['args'], $provider);
        }
    }

    private function transferUserTodos(int $old_user_id, int $user_id, $provider = 'tg'){
        $myTodo = new Todo($this->bot);
        $todos = $myTodo->getUserTodos($old_user_id, $provider);
        $user_todos = $myTodo->getUserTodos($user_id, $provider);
        $todos += $user_todos;

        foreach($todos as $todo){
            $myTodo->addTodo($user_id, $todo, $provider);
        }

    }
}
