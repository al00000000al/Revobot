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
/bank - get revolucia balance
/bash - Случайная шутка
/calc - Калькулятор
/casino - Казино на ревокоены
/channel - Случайный канал
/chat - Случайный чат
/chatid - get chat id
/coinflip - Орел или решка на ревокоены
/cmd - Создать команду (20R)
/defecator - Рандомное выполнение команд в чате
/delete - Удалить команду
/delmsg - Удааление сообщения бота
/donate - Купить ревокоины
/echo - Печатать
/editcode - Редактировать команду с кодом lua (0R)
/exchange - Курс
/execute - lua script
/fuckyou - Бот не отвечает 4ч на команды
/ai - Нейросеть
/aii - Очистить контекст и историю и ответить
/clearall - очистить историю и контекст
/clearcontext - Установить контекст
/clearhistory - Сбросить историю
/clearpermanencontext - Очистить перманентный контекст
/context - Контекст
/dellast - Удалить свое последнее сообщение из истории
/history - История
/permanentcontext - установить постоянный контекст
/help - Помощь
/huebot - напишите слово и получите х*еслово
/id - Мой ид
/idead - умрешь ли ты от того что на фото
/infa - Вероятность события
/mail - get answer from otveti mail ru
/mycommands - Мои комманды
/newcode - Создать команду с кодом lua (30R)
/or - Выбрать что-то одно
/pass - Пароль для переноса данных
/poll - Создать опрос в чате
/porfirevich - порфирьевич ии
/pukvy - риска миса
/vopros - Вопросы на коины
/rand - Случайное число
/rsend - Отправить R случ. польз.
/send - Отправить R польз.
/show - AI image generate DALL-E
/showcode - show code
/stable - create image
/stat - Статистика R
/storageget - Получить значение из ключа
/storageset - Сохранить значение в ключе
/summarize - /summarize ссылка
/talk - Лимит на разговоров
/time - Правильное время
/timer - Установка таймера ($)
/cancel - Отменить задачу
/todo - Список задач
/done - Отметить выполненым
/vision - send image
/vozrast - Сколько сегодня мне лет
/weather - Моя погода
/when - Узнать когда
/who - Узнать кто
/yn - Да или нет
/zn - получить ответы со znanija
";
    }
}