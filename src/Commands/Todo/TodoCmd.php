<?php


namespace Revobot\Commands\Todo;

use Revobot\Commands\BaseCmd;
use Revobot\Games\Todo;
use Revobot\Revobot;

class TodoCmd extends BaseCmd
{
    const KEYS = ['todo','туду','задачи'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'Список задач';

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
        $done_todos_count = $todo->getUserDoneTasks();
        $canceled_todos_count = $todo->getUserCanceledTasks();
        if (empty($this->input)) {
            return Todo::responseList(Todo::formatUserTodos($user_todos), $done_todos_count, $canceled_todos_count, $this->description);
        }
        $todos = explode(';', $this->input);
        foreach ($todos as $item) {
            $item = trim($item);
            $todo->saveUserTodos($item, $user_todos);
            $user_todos[] = $item;
        }
        if(count($todos) > 1){
            return 'Задачи добавлены!';
        }else{
            return 'Задача добавлена!';
        }
    }
}
