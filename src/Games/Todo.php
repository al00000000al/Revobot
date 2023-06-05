<?php

namespace Revobot\Games;

use Revobot\Revobot;

class Todo
{
    private const PMC_TODO_USER_KEY = 'todo_user_'; //.provider. $user_id;
    private const PMC_TODO_DONE_USER_KEY = 'todo_done_user_'; //.provider. $user_id;
    private const PMC_TODO_CANCELED_USER_KEY = 'todo_canceled_user_'; //.provider. $user_id;
    private Revobot $bot;
    private int $user_id;
    private string $provider;

    public function __construct(Revobot $bot) {
        $this->bot = $bot;
        $this->user_id = $this->bot->getUserId();
        $this->provider = $this->bot->provider;
    }

    /**
     * @return array
     */
    public function loadUserTodos(): array {
        return (array)json_decode($this->bot->pmc->get(self::PMC_TODO_USER_KEY . $this->provider . $this->user_id), true);
    }

    public function getUserTodos(int $user_id, string $provider = 'tg'): array {
        return (array)json_decode($this->bot->pmc->get(self::PMC_TODO_USER_KEY . $provider . $user_id), true);
    }

    /**
     * @param string $name
     * @param array $items
     * @return bool
     */
    public function saveUserTodos(string $name, array $items): bool {
        $new_items = $items;
        $new_items[] = $name;
       // sort($new_items);
        $this->bot->pmc->set(self::PMC_TODO_USER_KEY . $this->provider . $this->user_id, (string)json_encode($new_items));
        return true;
    }

    public function addTodo(int $user_id, string $item, string $provider = 'tg') {
        $items = $this->getUserTodos($user_id, $provider);
        $items[] = $item;
        $this->bot->pmc->set(self::PMC_TODO_USER_KEY . $provider . $user_id, (string)json_encode($items));
        return true;
    }

    /**
     * @param array $list
     * @return string
     */
    public static function formatUserTodos(array $list): string {
        $number = 1;
        $result = '';
        if (empty($list)) {
            return "У вас нет ничего";
        }
        foreach ($list as $item) {
            $result .= $number . '. ' . $item . "\n";
            $number++;
        }
        return $result;
    }

    /**
     * @param array $numbers
     * @param array $list
     * @return bool
     */
    public function deleteUserTodo(array $numbers, array $list): bool {
        if(empty($list)){
            return false;
        }

        foreach($numbers as $number){
            $iNumber = (int)$number;
            if(!isset($list[$iNumber - 1])){
                return false;
            }
            unset($list[$iNumber - 1]);
        }

        $list = array_values($list);
        $this->bot->pmc->set(self::PMC_TODO_USER_KEY . $this->provider . $this->user_id, (string)json_encode($list));
        return true;
    }

    public function incUserDoneTasks() {
        return $this->bot->pmc->incr(self::PMC_TODO_DONE_USER_KEY . $this->provider . $this->user_id, 1);
    }

    public function incUserCanceledTasks() {
        return $this->bot->pmc->incr(self::PMC_TODO_CANCELED_USER_KEY . $this->provider . $this->user_id, 1);
    }

    public function getUserDoneTasks() {
        return (int)$this->bot->pmc->get(self::PMC_TODO_DONE_USER_KEY . $this->provider . $this->user_id);
    }

    public function getUserCanceledTasks() {
        return (int)$this->bot->pmc->get(self::PMC_TODO_CANCELED_USER_KEY . $this->provider . $this->user_id);
    }

    public static function process(Todo $todo, array $numbers, $user_todos) {
        $tasks = [];
        $i = 1;
        foreach ($user_todos as $item) {
            if (in_array((string)$i, $numbers, true)) {
                $tasks[] = $item;
            }
            $i++;
        }
        return [$todo->deleteUserTodo($numbers, $user_todos), $tasks];
    }

    public static function responseNoTask($numbers) {
        if (count($numbers) > 1) {
            return 'Таких задач нет!';
        } else {
            return 'Такой задачи нет!';
        }
    }

    public static function responseBase($numbers, $user_todos, $tasks, $word, $word_many) {
        if (count($numbers) > 1) {
            $tasks_done_list = implode("\n -", $tasks);
            $word = 'Задачи '.$word_many;
        } else {
            $tasks_done_list = $tasks[0];
            $word = 'Задача '.$word_many;
        }
        return "{$word}:\n -{$tasks_done_list}\n\n{$user_todos}";
    }

    public static function reponseDone($numbers, $user_todos, $tasks) {
        return self::responseBase($numbers, $user_todos, $tasks, 'выполнена', 'выполнены');
    }

    public static function reponseCancel($numbers, $user_todos, $tasks) {
        return self::responseBase($numbers, $user_todos, $tasks, 'отменена', 'отменены');
    }

    public static function responseList($user_todos, $done_todos_count, $canceled_todos_count, $description) {
        return "{$user_todos}\n\nЗадач выполнено: {$done_todos_count}\nЗадач отменено: {$canceled_todos_count}\n\n{$description}";
    }

}
