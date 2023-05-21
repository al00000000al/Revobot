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
/alias - Создать алиас (10R)
/alive - Состояние бота
/answer - Ответить на вопрос
/balance - Узнать свой баланс
/bash - Случайная шутка
/calc - Калькулятор
/cancel - Отменить задачу
/casino - Казино на ревокоены
/chat - Случайный чат
/cmd - Создать команду (20R)
/delete - Удалить команду
/echo - Печатать
/exchange - Курс
/ai4 - gpt-4 (50R)
/ai - Нейросеть
/aii - Очистить контекст и историю и ответить
/clearall - очистить историю и контекст
/clearcontext - Установить контекст
/clearhistory - Сбросить историю
/context - Контекст
/history - История
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
/rsend - Отправить R случ. польз.
/send - Отправить R польз.
/stat - Статистика R
/talk - Лимит на разговоров
/time - Правильное время
/todo - Список задач
/done - Отметить выполненым
/vozrast - Сколько сегодня мне лет (нецелое)
/weather - Моя погода
/when - Узнать когда
/who - Узнать кто
/yn - Да или нет
";
    }
}