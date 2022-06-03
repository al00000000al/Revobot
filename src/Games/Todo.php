<?php

namespace Revobot\Games;

use Revobot\Revobot;

class Todo
{

    private const PMC_TODO_USER_KEY = 'todo_user_'; //.provider. $user_id;
    private Revobot $bot;
    private int $user_id;
    private string $provider;

    public function __construct(Revobot $bot)
    {
        $this->bot = $bot;
        $this->user_id = $this->bot->getUserId();
        $this->provider = $this->bot->provider;
    }

    /**
     * @return array
     */
    public function loadUserTodos(): array
    {
        return (array)json_decode($this->bot->pmc->get(self::PMC_TODO_USER_KEY . $this->provider . $this->user_id), true);
    }

    /**
     * @param string $name
     * @param array $items
     * @return bool
     */
    public function saveUserTodos(string $name, array $items): bool
    {
        $new_items = $items;
        $new_items[] = $name;
        sort($new_items);
        $this->bot->pmc->set(self::PMC_TODO_USER_KEY . $this->provider . $this->user_id, (string)json_encode($new_items));
        return true;
    }

    /**
     * @param array $list
     * @return string
     */
    public function formatUserTodos(array $list): string
    {
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
     * @param int $number
     * @param array $list
     * @return bool
     */
    public function deleteUserTodo(int $number, array $list): bool
    {
        if (empty($list) || !isset($list[$number - 1])) {
            return false;
        }
        unset($list[$number - 1]);
        $this->bot->pmc->set(self::PMC_TODO_USER_KEY . $this->provider . $this->user_id, (string)json_encode($list));
        return true;
    }
}
