<?php
/*
  Autogenerated code
*/
namespace Revobot;

class CommandsManager extends CommandsManagerBase
{
    public const COMMANDS = [
        'ai','ии','alias','алиас','alive','алив','answer','ответ','balance','баланс','bash','баш','calc','калк','cancel','отмена','передумал','casino','казино','chat','чат','cmd','кмд','команда','command','комманда','config','конфиг','delete','del','удалить','echo','эхо','excho','print','принт','exchange','currency','курс','help','хэлп','хлеп','помощь','id','ид','infa','инфа','key','ключ','key.edit','mycommands','моикомманды','or','ili','или','pass','пароль','poll','опрос','pukvy','пуквы','vopros','question','вопрос','rand','random','ранд','рандом','rsend','рсенд','send','сенд','stat','стат','stoyak','стояк','talk','толк','time','тайм','время','todo','туду','задачи','todo.done','done','готово','vozrast','возраст','weather','погода','pogoda','when','kogda','когда','who','кто','yn','дн'
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
            case 'ai':
case 'ии':
	$response = (new \Revobot\Commands\AiCmd($input, $bot))->exec();
	break;
case 'alias':
case 'алиас':
	$response = (new \Revobot\Commands\AliasCmd($input, $bot))->exec();
	break;
case 'alive':
case 'алив':
	$response = (new \Revobot\Commands\AliveCmd($input, $bot))->exec();
	break;
case 'answer':
case 'ответ':
	$response = (new \Revobot\Commands\AnswerCmd($input, $bot))->exec();
	break;
case 'balance':
case 'баланс':
	$response = (new \Revobot\Commands\BalanceCmd($input, $bot))->exec();
	break;
case 'bash':
case 'баш':
	$response = (new \Revobot\Commands\BashCmd($input))->exec();
	break;
case 'calc':
case 'калк':
	$response = (new \Revobot\Commands\CalcCmd($input))->exec();
	break;
case 'cancel':
case 'отмена':
case 'передумал':
	$response = (new \Revobot\Commands\CancelCmd($input, $bot))->exec();
	break;
case 'casino':
case 'казино':
	$response = (new \Revobot\Commands\CasinoCmd($input, $bot))->exec();
	break;
case 'chat':
case 'чат':
	$response = (new \Revobot\Commands\ChatCmd($input, $bot))->exec();
	break;
case 'cmd':
case 'кмд':
case 'команда':
case 'command':
case 'комманда':
	$response = (new \Revobot\Commands\CommandCmd($input, $bot))->exec();
	break;
case 'config':
case 'конфиг':
	$response = (new \Revobot\Commands\ConfigCmd($input, $bot))->exec();
	break;
case 'delete':
case 'del':
case 'удалить':
	$response = (new \Revobot\Commands\DeleteCmd($input, $bot))->exec();
	break;
case 'echo':
case 'эхо':
case 'excho':
case 'print':
case 'принт':
	$response = (new \Revobot\Commands\EchoCmd($input))->exec();
	break;
case 'exchange':
case 'currency':
case 'курс':
	$response = (new \Revobot\Commands\ExchangeCmd($input))->exec();
	break;
case 'help':
case 'хэлп':
case 'хлеп':
case 'помощь':
	$response = (new \Revobot\Commands\HelpCmd($input))->exec();
	break;
case 'id':
case 'ид':
	$response = (new \Revobot\Commands\IdCmd($input, $bot))->exec();
	break;
case 'infa':
case 'инфа':
	$response = (new \Revobot\Commands\InfaCmd($input, $bot))->exec();
	break;
case 'key':
case 'ключ':
	$response = (new \Revobot\Commands\Key\KeyCmd($input, $bot))->exec();
	break;
case 'key.edit':
	$response = (new \Revobot\Commands\Key\KeyEditCmd($input, $bot))->exec();
	break;
case 'mycommands':
case 'моикомманды':
	$response = (new \Revobot\Commands\MycommandsCmd($input, $bot))->exec();
	break;
case 'or':
case 'ili':
case 'или':
	$response = (new \Revobot\Commands\OrCmd($input))->exec();
	break;
case 'pass':
case 'пароль':
	$response = (new \Revobot\Commands\PassCmd($input, $bot))->exec();
	break;
case 'poll':
case 'опрос':
	$response = (new \Revobot\Commands\PollCmd($input, $bot))->exec();
	break;
case 'pukvy':
case 'пуквы':
	$response = (new \Revobot\Commands\PukvyCmd($input))->exec();
	break;
case 'vopros':
case 'question':
case 'вопрос':
	$response = (new \Revobot\Commands\QuestionCmd($input, $bot))->exec();
	break;
case 'rand':
case 'random':
case 'ранд':
case 'рандом':
	$response = (new \Revobot\Commands\RandCmd($input))->exec();
	break;
case 'rsend':
case 'рсенд':
	$response = (new \Revobot\Commands\RsendCmd($input, $bot))->exec();
	break;
case 'send':
case 'сенд':
	$response = (new \Revobot\Commands\SendCmd($input, $bot))->exec();
	break;
case 'stat':
case 'стат':
	$response = (new \Revobot\Commands\StatCmd($input, $bot))->exec();
	break;
case 'stoyak':
case 'стояк':
	$response = (new \Revobot\Commands\StoyakCmd($input, $bot))->exec();
	break;
case 'talk':
case 'толк':
	$response = (new \Revobot\Commands\TalkCmd($input, $bot))->exec();
	break;
case 'time':
case 'тайм':
case 'время':
	$response = (new \Revobot\Commands\TimeCmd($input, $bot))->exec();
	break;
case 'todo':
case 'туду':
case 'задачи':
	$response = (new \Revobot\Commands\TodoCmd($input, $bot))->exec();
	break;
case 'todo.done':
case 'done':
case 'готово':
	$response = (new \Revobot\Commands\TodoDoneCmd($input, $bot))->exec();
	break;
case 'vozrast':
case 'возраст':
	$response = (new \Revobot\Commands\VozrastCmd($input, $bot))->exec();
	break;
case 'weather':
case 'погода':
case 'pogoda':
	$response = (new \Revobot\Commands\WeatherCmd($input, $bot))->exec();
	break;
case 'when':
case 'kogda':
case 'когда':
	$response = (new \Revobot\Commands\WhenCmd($input))->exec();
	break;
case 'who':
case 'кто':
	$response = (new \Revobot\Commands\WhoCmd($input, $bot))->exec();
	break;
case 'yn':
case 'дн':
	$response = (new \Revobot\Commands\YnCmd($input, $bot))->exec();
	break;

            default:
            $response = '';
        }
        dbg_echo('cmd:' . $command . ',inp:' . $input . ',response: ' . $response . "\n");
        return $response;
    }
}
