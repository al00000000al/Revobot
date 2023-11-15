<?php

namespace Revobot\Commands;

use Revobot\Revobot;
use Revobot\Services\Providers\Tg;

class VisionCmd extends BaseCmd
{
    private Revobot $bot;
    const KEYS = ['vision','чтотам','прочитай','чтоделать'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'send image';

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
        $this->setDescription('отправь картинку и напиши /чтотам');
    }

    public function exec(): string
    {
        if(!isset($this->bot->raw_data['photo'])){
            return $this->description;
        }
        $photo = array_last_value($this->bot->raw_data['photo']);
        $file_id = (string)$photo['file_id'];

        $fileInfo = Tg::getFile($file_id);
        if(isset($fileInfo['result']['file_path'])){
            $filePath = (string)$fileInfo['result']['file_path'];
            file_put_contents('/home/opc/www/revobot/temp.jpg', Tg::file($filePath));
            $chat_id = (int)$this->bot->chat_id;
            exec("php /home/opc/www/revobot/gptdv.php $chat_id > /dev/null 2>&1 &");
        }

        return '';
    }
}
