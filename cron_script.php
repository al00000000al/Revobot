<?php
set_time_limit(0);
require_once __DIR__ . '/config.php';

$pmc = new Memcache;
$pmc->addServer('127.0.0.1', 11209);

$tasks = $pmc->get("tasks#");


while (true) {
    // Получите задачи из очереди и выполните их
    $queue = $pmc->get('queue') ?? [];

    foreach ($queue as $taskData) {
        if ($taskData['command'] === 'тяжелая_операция') {
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
    $pmc->set('queue', $queue);

    // Подождите некоторое время перед следующей итерацией
    sleep(1); // Например, каждую секунду
}
