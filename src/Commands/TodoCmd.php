<?php


namespace Revobot\Commands;

use Revobot\Games\Todo;
use Revobot\Revobot;

class TodoCmd extends BaseCmd
{
    private Revobot $bot;

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
        $this->setDescription('Добавить: /todo задача' . "\n" . 'Удалить: /todo.done номер задачи');
    }

    public function exec(): string
    {
        $todo = new Todo($this->bot);
        $user_todos = $todo->loadUserTodos();
        if (empty($this->input)) {
            return $todo->formatUserTodos($user_todos) . "\n\n" . $this->description;
        }
        $todo->saveUserTodos($this->input, $user_todos);
        return 'Задача добавлена!';
    }
}
