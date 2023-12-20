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

        $user_id = $this->bot->getUserId();
        $chat_id = $this->bot->chat_id;
        if (!Throttler::check($this->bot, $user_id, 'showcmd', 1, 60 * 60)) {
            return 'Подождите немного!';
        }
        $base_path = Config::get('base_path');
        PMC::set('dalle_input' . $user_id, $this->input);
        exec("php {$base_path}dalle.php $user_id $chat_id > /dev/null 2>&1 &");
        return '';
    }
}
