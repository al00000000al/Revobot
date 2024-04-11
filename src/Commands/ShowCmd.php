<?php

namespace Revobot\Commands;

use Revobot\Config;
use Revobot\Revobot;
use Revobot\Services\Dalle;
use Revobot\Services\Providers\Tg;
use Revobot\Util\PMC;
use Revobot\Util\Throttler;

class ShowCmd extends BaseCmd
{
    const KEYS = ['show', 'покажи', 'image', 'photo', 'фото', 'картинка'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'AI image generate DALL-E';
    private Revobot $bot;


    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->setDescription('/show a photo of nice dogs');
        $this->bot = $bot;
    }

    public function exec(): string
    {
        if (empty($this->input)) {
            return $this->description;
        }

        $user_id = userId();
        $chat_id = chatId();
        if (Throttler::check($user_id, 'showcmd', 20)) {
            return 'Больше нельзя сегодня';
        }
        $base_path = Config::get('base_path');
        PMC::set('dalle_input' . $user_id, $this->input);
        exec("cd {$base_path}/scripts && php dalle.php $user_id $chat_id > /dev/null 2>&1 &");
        return '';
    }
}
