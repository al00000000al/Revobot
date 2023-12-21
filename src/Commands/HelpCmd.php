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
/casino - Казино на ревокоены
/channel - Случайный канал
/chat - Случайный чат
/chatid - get chat id
/cmd - Создать команду (20R)
/delete - Удалить команду
/donate - Купить ревокоины
/echo - Печатать
/exchange - Курс
/execute - lua script
/fuckyou - Бот не отвечает 4ч на команды
/ai - Нейросеть
/aii - Очистить контекст и историю и ответить
/clearall - очистить историю и контекст
/clearcontext - Установить контекст
/clearhistory - Сбросить историю
/context - Контекст
/dellast - Удалить свое последнее сообщение из истории
/history - История
/help - Помощь
/id - Мой ид
/idead - умрешь ли ты от того что на фото
/infa - Вероятность события
/mycommands - Мои комманды
/newcode - Создать команду с кодом lua (30R)
/or - Выбрать что-то одно
/pass - Пароль для переноса данных
/poll - Создать опрос в чате
/pukvy - риска миса
/vopros - Вопросы на коины
/rand - Случайное число
/rsend - Отправить R случ. польз.
/send - Отправить R польз.
/show - AI image generate DALL-E
/stable - create image
/stat - Статистика R
/storageget - Получить значение из ключа
/storageset - Сохранить значение в ключе
/talk - Лимит на разговоров
/time - Правильное время
/cancel - Отменить задачу
/todo - Список задач
/done - Отметить выполненым
/vision - send image
/vozrast - Сколько сегодня мне лет (нецелое)
/weather - Моя погода
/when - Узнать когда
/who - Узнать кто
/yn - Да или нет
";
    }
}