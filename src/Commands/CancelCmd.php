<?php


namespace Revobot\Commands;


use Revobot\Games\Todo;
use Revobot\Revobot;

class CancelCmd extends BaseCmd
{
    const KEYS = ['cancel','отмена','передумал'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'Отменить задачу';
    private Revobot $bot;
    private int $user_id;
    private string $provider;

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->setDescription('/cancel номер_задачи');
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
            return 'Задача отменена!' ."\n\n" . $todo->formatUserTodos($user_todos);
        }
    }
}
