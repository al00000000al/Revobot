<?php
/*
  Autogenerated code
*/
namespace Revobot\Commands;

class HelpCmd extends BaseCmd
{
    const KEYS = ['help','хэлп','хлеп', 'помощь','start'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'Помощь';
    /**
     * @return string
     */
    public function exec(): string
    {
        return "Список команд бота:
/ai - Нейросеть
/aii - Очистить контекст и историю и ответить
/alias - Создать алиас (10R)
/alive - Состояние бота
/answer - Ответить на вопрос
/balance - Узнать свой баланс
/bash - Случайная шутка
/calc - Калькулятор
/cancel - Отменить задачу
/casino - Казино на ревокоены
/chat - Случайный чат
/clearall - очистить историю и контекст
/cmd - Создать команду (20R)
/context - Установить контекст нейросети
/delete - Удалить команду
/echo - Печатать
/exchange - Курс
/help - Помощь
/id - Мой ид
/infa - Вероятность события
/mycommands - Мои комманды
/or - Выбрать что-то одно
/pass - Пароль для переноса данных
/poll - Создать опрос в чате
/pukvy - риска миса
/vopros - Вопросы на коины
/rand - Случайное число
/reset - Сбросить историю
/rsend - Отправить R случ. польз.
/send - Отправить R польз.
/stat - Статистика R
/talk - Лимит на разговоров
/time - Правильное время
/todo - Список задач
/todo.done - Отметить выполненым
/vozrast - Сколько сегодня мне лет (нецелое)
/weather - Моя погода
/when - Узнать когда
/who - Узнать кто
/yn - Да или нет
";
    }
}