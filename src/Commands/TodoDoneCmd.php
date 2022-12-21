<?php

namespace Revobot\Commands;

use Revobot\Games\Todo;
use Revobot\Revobot;

class TodoDoneCmd extends BaseCmd
{
    const KEYS = ['todo.done','done','готово'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'Отметить выполненым';

    private Revobot $bot;
    private int $user_id;
    private string $provider;

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->setDescription('/дон номер_задачи');
        $this->bot = $bot;
        $this->user_id = $this->bot->getUserId();
        $this->provider = $this->bot->provider;
    }

    public function exec(): string
    {
        $numbers = explode(' ', $this->input);

        $todo = new Todo($this->bot);
        $user_todos = $todo->loadUserTodos();
        $tasks = [];
        $i = 1;
        foreach ($user_todos as $item) {
            if (in_array((string)$i, $numbers, true)) {
                $tasks[] = $item;
            }
            $i++;
        }
        $result = $todo->deleteUserTodo($numbers, $user_todos);
        if (!$result) {
            if (count($numbers) > 1) {
                return 'Таких задач нет!';
            } else {
                return 'Такой задачи нет!';
            }
        } else {
            $user_todos = $todo->loadUserTodos();
            if (count($numbers) > 1) {
                return 'Задачи выполнены: -' . implode("\n-", $tasks) ."\n\n" . $todo->formatUserTodos($user_todos);
            } else {
                return 'Задача: -' . $tasks[0] . ' выполнена!' ."\n\n" . $todo->formatUserTodos($user_todos);
            }
        }
    }
}
