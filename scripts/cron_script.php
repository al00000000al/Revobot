<?php

use Revobot\Util\PMC;

set_time_limit(0);
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../vendor/autoload.php';

$tasks = PMC::get("tasks#");

while (true) {
    // Получите задачи из очереди и выполните их
    $queue = PMC::get('queue') ?? [];

    foreach ($queue as $taskData) {
        if ($taskData['command'] === 'ai') {
            // Выполните тяжелую операцию с данными $taskData['data']
            // Здесь можно использовать свой код для обработки операции
            // ...

            // После выполнения задачи, удалите ее из очереди
            $index = array_search($taskData, $queue);
            if ($index !== false) {
                unset($queue[$index]);
            }
        }
    }

    // Обновите очередь в Memcache
    PMC::set('queue', $queue);

    // Подождите некоторое время перед следующей итерацией
    sleep(1); // Например, каждую секунду
}
