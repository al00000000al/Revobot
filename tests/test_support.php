<?php
$hl = new Memcache;
$hl->addServer('127.0.0.1', 11247);
$question_id = 1; //ID вопроса. Не обязательно
$user_id = 1; //ID юзера. Не обязательно
$mark = 5; //Оценка
$question  = "Где найти Content Manager ?"; //вопрос

// $answer = "Спам для пидоров"; //Ответ

$question = iconv('UTF-8', 'WINDOWS-1251', $question);
// $answer = iconv('UTF-8', 'WINDOWS-1251', $answer);

// var_dump($hl->getStats());

// $hl->set("answer{$question_id},{$user_id},{$mark}", $question . "\t" . $answer);

$random_tag = mt_rand(111, 999);
$hl->set("question1",  $question);
$result = $hl->get("answer1,1,1#30");
print_r($result);
if ($result) {
    $answ = $result[0][1];
    echo $answ . PHP_EOL;
}

// $hl->get("delete_answer{$question_id}");
