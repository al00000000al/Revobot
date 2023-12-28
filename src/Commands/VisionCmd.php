<?php

namespace Revobot\Commands;

use Revobot\Config;
use Revobot\Games\AI\GptPMC;
use Revobot\Revobot;
use Revobot\Services\Providers\Tg;

class VisionCmd extends BaseCmd
{
    private Revobot $bot;
    const KEYS = ['vision', 'чтотам', 'прочитай', 'чтоделать'];
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

        if (!empty($this->input)) {
            $input = $this->input;
        } else {
            $input = 'Че тут? Напиши очень кратко';
        }
        $user_id = userId();
        $GptPMC = new GptPMC($user_id, $this->bot->provider);
        $GptPMC->setInput($input);

        $base_path = Config::get('base_path');

        $fileInfo = Tg::getFile($file_id);
        if (isset($fileInfo['result']['file_path'])) {
            $filePath = (string)$fileInfo['result']['file_path'];
            file_put_contents($base_path . 'temp.jpg', Tg::file($filePath));
            $chat_id = (int)$this->bot->chat_id;
            exec("cd {$base_path}/scripts && php gptdv.php $chat_id $user_id $message_id > /dev/null 2>&1 &");
        }

        return '';
    }
}
