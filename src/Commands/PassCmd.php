<?php

namespace Revobot\Commands;

use Revobot\Commands\Custom\CustomCmd;
use Revobot\Games\Predictor\Utils;
use Revobot\Games\Predictor\YesNo;
use Revobot\Games\Todo;
use Revobot\Revobot;
use Revobot\Util\Hash;
use Revobot\Util\Strings;

class PassCmd extends BaseCmd
{

    const KEYS = ['pass','пароль'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'Пароль для переноса данных';
    private Revobot $bot;


    public function __construct($input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
        $this->setDescription('Введите /pass');
    }

    /**
     * @return string
     */
    public function exec(): string
    {
        $user_id = (int) $this->bot->getUserId();
        $chat_id = (int)$this->bot->chat_id;

        if($chat_id !== $user_id){
            return "Эта комманда работает только в личном чате с ботом";
        }
        if(empty($this->input)){
            return "Ваш код для переноса данных: \n\n" . $this->generateRestorePass($user_id);
        }

        if(! $this->checkRecoveryPassword($this->input, $user_id)){
            return "Неверный код переноса или пользователь!";
        }

        $old_user_id = (int) self::getUserFromPassword($this->input);
        self::transfer($old_user_id, $user_id, $this->bot->provider);

        return "Данные успешно перенесены на ваш аккаунт!";
    }

    public function transfer(int $old_user_id, int $user_id, string $provider = 'tg'){
        $this->transferUserCommands($old_user_id, $user_id, $provider);
        $this->transferUserTodos($old_user_id, $user_id, $provider);
        $this->transferUserMoney($old_user_id, $user_id, $provider);
    }

    private function generateRestorePass(int $user_id) {
        $randomString = bin2hex(openssl_random_pseudo_bytes(12));
        $hashedData = (string) substr(Hash::generate($user_id), 0, 12);
        $xor_key = Strings::xor($hashedData, $randomString);
        $xor_user_id = Strings::xor(dechex($user_id), $randomString);
        return $randomString.$xor_key.$xor_user_id;
    }

    private function checkRecoveryPassword(string $recoveryPassword, int $current_user_id) : bool {
        $randomString = (string) substr($recoveryPassword, 0, 12);
        $xor_key = (string) substr($recoveryPassword, 24, 12);
        $xor_user_id = (string) substr($recoveryPassword, 36);
        $expectedXorUserId = (int) hexdec(Strings::xor($xor_user_id, $randomString));
        $hashedData = substr(Hash::generate($expectedXorUserId), 0, 12);
        $expectedXorKey = Strings::xor($xor_key, $randomString);
        return ($expectedXorKey === $hashedData) && ($current_user_id !== $expectedXorUserId);
    }

    private function getUserFromPassword(string $recoveryPassword) {
        $randomString = (string) substr($recoveryPassword, 0, 12);
        $xor_user_id = (string) substr($recoveryPassword, 36);
        $expectedXorUserId = (int) hexdec(Strings::xor($xor_user_id, $randomString));
        return $expectedXorUserId;
    }

    private function transferUserCommands(int $old_user_id, int $user_id, string $provider = 'tg'){
        $myCustomCmd = new CustomCmd($this->bot);
        $commands = $myCustomCmd->getUserCommands($old_user_id, $provider);
        foreach($commands as $cmd){
            $cmd_data = (array)$myCustomCmd->getCustomCmd((string)$cmd);
            $myCustomCmd->deleteCommand($old_user_id, (string)$cmd, $provider);
            $myCustomCmd->addCommand($user_id, (string)$cmd, (int)$cmd_data['command_type'], (array)$cmd_data['args'], $provider);
        }
    }

    private function transferUserMoney(int $old_user_id, int $user_id, string $provider = 'tg') {
        $from_coins = (int)$this->bot->pmc->get('money_'.$provider.$old_user_id);
        $to_coins = (int)$this->bot->pmc->get('money_'.$provider.$user_id);
        $result = $to_coins + $from_coins;
        $this->bot->pmc->set('money_'.$provider.$user_id, $result);
        $this->bot->pmc->set('money_'.$provider.$old_user_id, 0);
    }

    private function transferUserTodos(int $old_user_id, int $user_id, $provider = 'tg'){
        $myTodo = new Todo($this->bot);
     //   $todos = (array)$myTodo->getUserTodos($old_user_id, $provider);
        $user_todos = (array)$myTodo->getUserTodos($user_id, $provider);
        $todos = $user_todos;

        foreach($todos as $todo){
            $myTodo->addTodo($user_id, (string)$todo, $provider);
        }

    }
}
