<?php


namespace Revobot\Commands;


use Revobot\Games\Todo;
use Revobot\Revobot;

class TodoDoneCmd extends BaseCmd
{
    private Revobot $bot;
    private int $user_id;
    private string $provider;

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->setDescription('/todo.done номер_задачи');
        $this->bot = $bot;
        $this->user_id = $this->bot->getUserId();
        $this->provider = $this->bot->provider;
    }

    public function exec(): string
    {
        $number = (int)$this->input;
        if ($number < 1 || $number > PHP_INT_MAX) {
            return 'Неверный номер задачи';
        }
        $todo = new Todo($this->bot);
        $user_todos = $todo->loadUserTodos();
        $result = $todo->deleteUserTodo($number, $user_todos);
        if (!$result) {
            return 'Такой задачи нет';
        } else {
            return 'Задача выполнена!';
        }
    }
}
