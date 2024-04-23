<?php
$hl = new Memcache;
$hl->addServer('127.0.0.1', 11247);
$question_id = 1; //ID вопроса. Не обязательно
$user_id = 1; //ID юзера. Не обязательно
$mark = 5; //Оценка
$question  = "Кто проживает на дне океана?"; //вопрос
$answer = "Губка боб кводратные штоны!"; //Ответ

$question = iconv('UTF-8', 'WINDOWS-1251', $question);
$answer = iconv('UTF-8', 'WINDOWS-1251', $answer);

// var_dump($hl->getStats());

// $hl->set("answer{$question_id},{$user_id},{$mark}", $question . "\t" . $answer);

// $random_tag = mt_rand(111, 999);
var_dump($hl->set("question1",  "Кто проживает на дне океана?"));
var_dump($hl->get("answer1,1,1[#30]"));

// $hl->get("delete_answer{$question_id}");
