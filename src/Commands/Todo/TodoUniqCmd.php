<?php

namespace Revobot\Commands\Todo;

use Revobot\Commands\BaseCmd;
use Revobot\Games\Todo;
use Revobot\Revobot;

class TodoUniqCmd extends BaseCmd
{
    const KEYS = ['todo.uniq', 'туник'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'удалить дубли в туду';
    private Revobot $bot;

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
        $this->setDescription('/todo.uniq удалить дубли в туду');
    }

    public function exec(): string
    {
        $todo = new Todo($this->bot);
        $user_todos = $todo->loadUserTodos();
        $user_todos = array_unique($user_todos);
        $todo->updateUserTodos($user_todos);
        return Todo::formatUserTodos($user_todos);
    }
}
