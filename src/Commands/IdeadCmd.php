<?php

namespace Revobot\Commands;

use Revobot\Config;
use Revobot\Revobot;
use Revobot\Services\Providers\Tg;

class IdeadCmd extends BaseCmd
{
    private Revobot $bot;
    const KEYS = ['idead', 'яумру'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'умрешь ли ты от того что на фото';

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
        $this->setDescription('Отправь фото и напиши /яумру');
    }


    public function exec(): string
    {
        if (isset($this->bot->raw_data['photo'])) {
            $photo = array_last_value($this->bot->raw_data['photo']);
        } elseif (isset($this->bot->raw_data['reply_to_message']['photo'])) {
            $photo = array_last_value($this->bot->raw_data['reply_to_message']['photo']);
        } elseif (isset($this->bot->raw_data['video']['thumbnail'])) {
            $photo = array_last_value($this->bot->raw_data['video']['thumbnail']);
        } elseif (isset($this->bot->raw_data['reply_to_message']['video']['thumbnail'])) {
            $photo = array_last_value($this->bot->raw_data['reply_to_message']['video']['thumbnail']);
        } else {
            return $this->description;
        }

        $file_id = (string)$photo['file_id'];
        $message_id = $this->bot->raw_data['message_id'];

        $fileInfo = Tg::getFile($file_id);
        $base_path = Config::get('base_path');
        if (isset($fileInfo['result']['file_path'])) {
            $filePath = (string)$fileInfo['result']['file_path'];
            file_put_contents($base_path . 'temp.jpg', Tg::file($filePath));
            $chat_id = (int)$this->bot->chat_id;
            exec("php {$base_path}gptdv2.php $chat_id $message_id > /dev/null 2>&1 &");
        }

        return '';
    }
}
