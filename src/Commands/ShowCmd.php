<?php

    namespace Revobot\Commands;
    use Revobot\Config;
    use Revobot\Revobot;
use Revobot\Services\Dalle;
use Revobot\Services\Kandinski;
use Revobot\Services\Providers\Tg;
    use Revobot\Util\Curl;

    class ShowCmd extends BaseCmd
    {
        const KEYS = ['show','покажи', 'image', 'photo','фото', 'картинка'];
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
            if (empty($this->input)){
                return $this->description;
            }

            list($status, $result) = Dalle::generate($this->input);
            if($status === -1) {
                return $result;
            }
            Tg::sendPhoto($this->bot->chat_id, $result, $this->input);

            return '';
        }
    }
