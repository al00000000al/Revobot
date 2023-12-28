<?php

namespace Revobot\Commands;

use Revobot\Config;
use Revobot\Revobot;
use Revobot\Services\Providers\Tg;
use Revobot\Util\PMC;

class StableDiffusionCmd extends BaseCmd
{
    private Revobot $bot;
    const KEYS = ['stable', 'sd', 'сд'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'create image';
    const PMC_KEY = 'stable_diffusion_';

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->setDescription('/sd prompt');
        $this->bot = $bot;
    }

    public function exec(): string
    {

        if (empty($this->input)) {
            return $this->description;
        }
        $user_id = userId();
        $chat_id = chatId();

        if (isset($this->bot->raw_data['photo'])) {
            $photo = array_last_value($this->bot->raw_data['photo']);
        } elseif (isset($this->bot->raw_data['reply_to_message']['photo'])) {
            $photo = array_last_value($this->bot->raw_data['reply_to_message']['photo']);
        } else {
            $photo = null;
        }
        if ($photo) {
            $file_id = (string)$photo['file_id'];
            $file_info = Tg::getFile($file_id);
            $base_path = Config::get('base_path');
            if (isset($file_info['result']['file_path'])) {
                $file_path = (string)$file_info['result']['file_path'];
                PMC::set(self::PMC_KEY . '_' . $user_id, ['chat_id' => $chat_id, 'user_id' => $user_id, 'prompt' => $this->input, 'photo' => $file_path]);
            }
        } else {
            PMC::set(self::PMC_KEY . '_' . $user_id, ['chat_id' => $chat_id, 'user_id' => $user_id, 'prompt' => $this->input]);
        }





        return '';
    }
}
