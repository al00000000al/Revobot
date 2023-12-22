<?php

namespace Revobot\Commands\Todo;

use Revobot\Commands\BaseCmd;
use Revobot\Games\Todo;
use Revobot\Revobot;

class TodoDoneCmd extends BaseCmd
{
    const KEYS = ['done', 'todo.done', 'готово'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'Отметить выполненым';

    private Revobot $bot;
    private int $user_id;
    private string $provider;

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->setDescription('/done номер_задачи');
        $this->bot = $bot;
        $this->user_id = $this->bot->getUserId();
        $this->provider = $this->bot->provider;
    }

    public function exec(): string
    {
        $numbers = explode(' ', $this->input);

        $todo = new Todo($this->bot);
        $user_todos = $todo->loadUserTodos();
        list($result, $tasks) = Todo::process($todo, $numbers, $user_todos);

        if (!$result) {
            return Todo::responseNoTask($numbers);
        } else {
            $user_todos_list = Todo::formatUserTodos($todo->loadUserTodos());
            $todo->incUserDoneTasks();
            return Todo::reponseDone($numbers, $user_todos_list, $tasks);
        }
    }
}
