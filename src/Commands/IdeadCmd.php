<?php

    namespace Revobot\Commands;

use Revobot\Revobot;
use Revobot\Services\Providers\Tg;

    class IdeadCmd extends BaseCmd
    {
        private Revobot $bot;
        const KEYS = ['idead','яумру'];
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
            if(!isset($this->bot->raw_data['photo']) || !isset($this->bot->raw_data['reply_to_message']['photo'])){
                return $this->description;
            }
            if(isset($this->bot->raw_data['photo'])) {
                $photo = array_last_value($this->bot->raw_data['photo']);
            } else {
                $photo = array_last_value($this->bot->raw_data['reply_to_message']['photo']);
            }

            $file_id = (string)$photo['file_id'];

            $fileInfo = Tg::getFile($file_id);
            if(isset($fileInfo['result']['file_path'])){
                $filePath = (string)$fileInfo['result']['file_path'];
                file_put_contents('/home/opc/www/revobot/temp.jpg', Tg::file($filePath));
                $chat_id = (int)$this->bot->chat_id;
                exec("php /home/opc/www/revobot/gptdv2.php $chat_id > /dev/null 2>&1 &");
            }

            return '';
        }
    }
