<?php
/*
  Autogenerated code
*/
namespace Revobot;

class CommandsManager extends CommandsManagerBase
{
    public const COMMANDS = [
        'alias','алиас','alive','алив','answer','ответ','balance','баланс','bash','баш','calc','калк','casino','казино','channel','канал','chat','чат','chatid','чатид','cmd','кмд','команда','command','комманда','config','конфиг','delete','del','удалить','donate','донат','echo','эхо','excho','print','принт','editcode','редактироватькод','exchange','currency','курс','execute','food','fuckyou','идинахуй','пошланахуй','ai','ии','aii','иии','clearall','сброс','clearcontext','ксброс','cc','clearhistory','ch','исброс','context','контекст','c','кнт','dellast','d','делпослед','history','h','ист','история','help','хэлп','хлеп','помощь','start','id','ид','idead','яумру','infa','инфа','key','ключ','key.edit','mycommands','моикомманды','newcode','новыйкод','or','ili','или','pass','пароль','poll','опрос','pukvy','пуквы','vopros','question','вопрос','rand','random','ранд','рандом','rsend','рсенд','send','сенд','show','покажи','image','photo','фото','картинка','showcode','stable','sd','сд','stat','стат','storageget','storageset','stoyak','стояк','talk','толк','time','тайм','время','cancel','отмена','передумал','todo','туду','задачи','done','todo.done','готово','vision','чтотам','прочитай','чтоделать','vozrast','возраст','weather','погода','pogoda','when','kogda','когда','who','кто','yn','дн'
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
case 'casino':
case 'казино':
	$response = (new \Revobot\Commands\CasinoCmd($input, $bot))->exec();
	break;
case 'channel':
case 'канал':
	$response = (new \Revobot\Commands\ChannelCmd($input, $bot))->exec();
	break;
case 'chat':
case 'чат':
	$response = (new \Revobot\Commands\ChatCmd($input, $bot))->exec();
	break;
case 'chatid':
case 'чатид':
	$response = (new \Revobot\Commands\ChatIdCmd($input, $bot))->exec();
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
case 'donate':
case 'донат':
	$response = (new \Revobot\Commands\DonateCmd($input))->exec();
	break;
case 'echo':
case 'эхо':
case 'excho':
case 'print':
case 'принт':
	$response = (new \Revobot\Commands\EchoCmd($input))->exec();
	break;
case 'editcode':
case 'редактироватькод':
	$response = (new \Revobot\Commands\EditCodeCmd($input, $bot))->exec();
	break;
case 'exchange':
case 'currency':
case 'курс':
	$response = (new \Revobot\Commands\ExchangeCmd($input))->exec();
	break;
case 'execute':
	$response = (new \Revobot\Commands\ExecuteCmd($input, $bot))->exec();
	break;
case 'food':
	$response = (new \Revobot\Commands\FoodCmd($input, $bot))->exec();
	break;
case 'fuckyou':
case 'идинахуй':
case 'пошланахуй':
	$response = (new \Revobot\Commands\FuckYouCmd($input, $bot))->exec();
	break;
case 'ai':
case 'ии':
	$response = (new \Revobot\Commands\Gpt\AiCmd($input, $bot))->exec();
	break;
case 'aii':
case 'иии':
	$response = (new \Revobot\Commands\Gpt\AiiCmd($input, $bot))->exec();
	break;
case 'clearall':
case 'сброс':
	$response = (new \Revobot\Commands\Gpt\ClearAllCmd($input, $bot))->exec();
	break;
case 'clearcontext':
case 'ксброс':
case 'cc':
	$response = (new \Revobot\Commands\Gpt\ClearContextCmd($input, $bot))->exec();
	break;
case 'clearhistory':
case 'ch':
case 'исброс':
	$response = (new \Revobot\Commands\Gpt\ClearHistoryCmd($input, $bot))->exec();
	break;
case 'context':
case 'контекст':
case 'c':
case 'кнт':
	$response = (new \Revobot\Commands\Gpt\ContextCmd($input, $bot))->exec();
	break;
case 'dellast':
case 'd':
case 'делпослед':
	$response = (new \Revobot\Commands\Gpt\DelLastCmd($input, $bot))->exec();
	break;
case 'history':
case 'h':
case 'ист':
case 'история':
	$response = (new \Revobot\Commands\Gpt\HistoryCmd($input, $bot))->exec();
	break;
case 'help':
case 'хэлп':
case 'хлеп':
case 'помощь':
case 'start':
	$response = (new \Revobot\Commands\HelpCmd($input))->exec();
	break;
case 'id':
case 'ид':
	$response = (new \Revobot\Commands\IdCmd($input, $bot))->exec();
	break;
case 'idead':
case 'яумру':
	$response = (new \Revobot\Commands\IdeadCmd($input, $bot))->exec();
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
case 'newcode':
case 'новыйкод':
	$response = (new \Revobot\Commands\NewcodeCmd($input, $bot))->exec();
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
case 'show':
case 'покажи':
case 'image':
case 'photo':
case 'фото':
case 'картинка':
	$response = (new \Revobot\Commands\ShowCmd($input, $bot))->exec();
	break;
case 'showcode':
	$response = (new \Revobot\Commands\ShowCodeCmd($input, $bot))->exec();
	break;
case 'stable':
case 'sd':
case 'сд':
	$response = (new \Revobot\Commands\StableDiffusionCmd($input, $bot))->exec();
	break;
case 'stat':
case 'стат':
	$response = (new \Revobot\Commands\StatCmd($input, $bot))->exec();
	break;
case 'storageget':
	$response = (new \Revobot\Commands\StorageGetCmd($input, $bot))->exec();
	break;
case 'storageset':
	$response = (new \Revobot\Commands\StorageSetCmd($input, $bot))->exec();
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
case 'cancel':
case 'отмена':
case 'передумал':
	$response = (new \Revobot\Commands\Todo\TodoCancelCmd($input, $bot))->exec();
	break;
case 'todo':
case 'туду':
case 'задачи':
	$response = (new \Revobot\Commands\Todo\TodoCmd($input, $bot))->exec();
	break;
case 'done':
case 'todo.done':
case 'готово':
	$response = (new \Revobot\Commands\Todo\TodoDoneCmd($input, $bot))->exec();
	break;
case 'vision':
case 'чтотам':
case 'прочитай':
case 'чтоделать':
	$response = (new \Revobot\Commands\VisionCmd($input, $bot))->exec();
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
