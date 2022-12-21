<?php

namespace Revobot\Commands;

use Revobot\Commands\Custom\CustomCmd;
use Revobot\Revobot;

class MycommandsCmd extends BaseCmd
{

    const KEYS = ['mycommands','моикомманды'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'Мои комманды';
    private Revobot $bot;

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
    }

    public function exec(): string
    {
       $user_commands = (new CustomCmd($this->bot))->getUserCommands($this->bot->getUserId());
       if(!$user_commands){
           return "У пользователя нет комманд";
       }
       $str=  'Команды пользователя '.$this->bot->getUserNick().":\n";
       foreach($user_commands as $command){
           $str .= '/'.$command."\n";
       }
       return $str;
    }

}
