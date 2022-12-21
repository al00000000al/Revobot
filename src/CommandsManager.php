<?php

namespace Revobot;

use Revobot\Neural\Answers;

class CommandsManager extends CommandsManagerBase
{
    public const COMMANDS = [
        'alive',
        'алив',
        'bash',
        'баш',
        'calc',
        'help',
        'хэлп',
        'хлеп',
        'помощь',
        'infa',
        'инфа',
        'pukvy',
        'пуквы',
        'rand',
        'ранд',
        'when',
        'когда',
        'yn',
        'дн',
        'chat',
        'чат',
        'чят',
        'time',
        'время',
        'balance',
        'баланс',
        'stat',
        'стат',
        'echo',
        'print',
        'принт',
        'excho',
        'config',
        'key',
        'конфиг',
        'key.edit',
        'send',
        'сенд',
        'rsend',
        'рсенд',
        'talk',
        'толк',
        'кто',
        'who',
        'ии',
        'ai',
        'ор',
        'или',
        'or',
        'ili',
        'alias',
        'алиас',
        'mycommands',
        'моикоманды',
        'command',
        'cmd',
        'комманда',
        'команда',
        'del',
        'delete',
        'удалить',
        'вопрос',
        'ответ',
        'answer',
        'question',
        'quiz',
        'id',
        'ид',
        'casino',
        'казино',
        'weather',
        'погода',
        'todo',
        'туду',
        'todos',
        'задачи',
        'todolist',
        'todo.done',
        'todo.delete',
        'exchange',
        'currency',
        'курс',
        'cancel',
        'передумал',
        'отмена',
        'stoyak',
        'стояк',
     //   'tr'
    ];


    /**
     * @param Revobot $bot
     * @param string $command
     * @param string $input
     * @return string
     */
    public static function run(Revobot $bot, string $command, string $input): string
    {
        switch ($command) {
            case 'alive' :
            case 'алив':
                $response = (new \Revobot\Commands\AliveCmd($input, $bot))->exec();
                break;
            case 'bash' :
            case 'баш':
                $response = (new \Revobot\Commands\BashCmd($input))->exec();
                break;
            case 'calc':
                $response = (new \Revobot\Commands\CalcCmd($input))->exec();
                break;
            case 'help' :
            case 'хэлп':
            case 'хлеп':
            case 'помощь':
                $response = (new \Revobot\Commands\HelpCmd($input))->exec();
                break;
            case 'infa' :
            case 'инфа' :
                $response = (new \Revobot\Commands\InfaCmd($input, $bot))->exec();
                break;
            case 'pukvy' :
            case 'пуквы' :
                $response = (new \Revobot\Commands\PukvyCmd($input))->exec();
                break;
            case 'rand' :
            case 'ранд' :
                $response = (new \Revobot\Commands\RandCmd($input))->exec();
                break;
            case 'when' :
            case 'когда' :
                $response = (new \Revobot\Commands\WhenCmd($input))->exec();
                break;
            case 'yn' :
            case 'дн' :
                $response = (new \Revobot\Commands\YnCmd($input, $bot))->exec();
                break;
            case 'chat':
            case 'чат':
            case 'чят':
                $response = (new \Revobot\Commands\ChatCmd($input, $bot))->exec();
                break;
            case 'time':
            case 'время':
                $response = (new \Revobot\Commands\TimeCmd($input, $bot))->exec();
                break;
            case 'balance':
            case 'баланс':
                $response = (new \Revobot\Commands\BalanceCmd($input, $bot))->exec();
                break;
            case 'stat':
            case 'стат':
                $response = (new \Revobot\Commands\StatCmd($input, $bot))->exec();
                break;
            case 'echo':
            case 'print':
            case 'принт':
            case 'excho':
                $response = (new \Revobot\Commands\EchoCmd($input))->exec();
                break;
            case 'config':
            case 'key':
            case 'конфиг':
                $response = (new \Revobot\Commands\Key\KeyCmd($input, $bot))->exec();
                break;
            case 'key.edit':
                $response = (new \Revobot\Commands\Key\KeyEditCmd($input, $bot))->exec();
                break;
            case 'send':
            case 'сенд':
                $response = (new \Revobot\Commands\SendCmd($input, $bot))->exec();
                break;
            case 'rsend':
            case 'рсенд':
                $response = (new \Revobot\Commands\RsendCmd($input, $bot))->exec();
                break;
            case 'talk':
            case 'толк':
                $response = (new \Revobot\Commands\TalkCmd($input, $bot))->exec();
                break;
            case 'кто':
            case 'who':
                $response = (new \Revobot\Commands\WhoCmd($input, $bot))->exec();
                break;
            case 'ии':
            case 'ai':
                $response = Answers::getAnswer($input);
                break;
            case 'ор':
            case 'или':
            case 'or':
            case 'ili':
                $response = (new \Revobot\Commands\OrCmd($input))->exec();
                break;
            case  'alias':
            case 'алиас':
                $response = (new \Revobot\Commands\AliasCmd($input, $bot))->exec();
                break;
            case 'mycommands':
            case 'моикоманды':
                $response = (new \Revobot\Commands\MycommandsCmd($input, $bot))->exec();
                break;
            case  'command':
            case  'cmd':
            case  'комманда':
            case  'команда':
                $response = (new \Revobot\Commands\CommandCmd($input, $bot))->exec();
                break;
            case  'delete':
            case  'del':
            case  'удалить':
                $response = (new \Revobot\Commands\DeleteCmd($input, $bot))->exec();
                break;
            case  'вопрос':
            case  'question':
                $response = (new \Revobot\Commands\QuestionCmd($input, $bot))->exec();
                break;
            case  'answer':
            case  'ответ':
                $response = (new \Revobot\Commands\AnswerCmd($input, $bot))->exec();
                break;
            case 'id':
            case 'ид':
                $response = (new \Revobot\Commands\IdCmd($input, $bot))->exec();
                break;
            case 'casino':
            case 'казино':
                $response = (new \Revobot\Commands\CasinoCmd($input, $bot))->exec();
                break;
            case 'weather':
            case 'погода':
                $response = (new \Revobot\Commands\WeatherCmd($input, $bot))->exec();
                break;
            case 'todo':
            case 'туду':
            case 'todos':
            case 'задачи':
            case 'todolist':
                $response = (new \Revobot\Commands\TodoCmd($input, $bot))->exec();
                break;
            case 'todo.done':
            case 'done':
                $response = (new \Revobot\Commands\TodoDoneCmd($input, $bot))->exec();
                break;
            case 'exchange':
            case 'currency':
            case 'курс':
                $response = (new \Revobot\Commands\ExchangeCmd($input))->exec();
                break;
            case 'cancel':
            case 'передумал':
            case 'отмена':
            case 'todo.delete':
                $response = (new \Revobot\Commands\CancelCmd($input, $bot))->exec();
                break;
            case 'stoyak':
            case 'стояк':
                $response = (new \Revobot\Commands\StoyakCmd($input, $bot))->exec();
                break;
            case 'tr':
                $response = '';
          //      $response = (new \Revobot\Commands\TranslateCmd($input))->exec();
                break;
            default:
                $response = '';
        }

        if(0){
        dbg_echo('cmd:' . $command . ',inp:' . $input . ',response: ' . $response . "\n");
}
        return $response;
    }


}
