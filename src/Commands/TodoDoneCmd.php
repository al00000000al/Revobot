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
        $numbers = explode(' ', $this->input);
        
        $todo = new Todo($this->bot);
        $user_todos = $todo->loadUserTodos();
        $result = $todo->deleteUserTodo($numbers, $user_todos);
        if (!$result) {
            return 'Такой задачи нет';
        } else {
            $user_todos = $todo->loadUserTodos();
            return 'Задача выполнена!' ."\n\n" . $todo->formatUserTodos($user_todos);
        }
    }
}
