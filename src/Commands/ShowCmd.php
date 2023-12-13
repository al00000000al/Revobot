<?php

    namespace Revobot\Commands;
    use Revobot\Config;
    use Revobot\Revobot;
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
            return "нет";
            // $photo = Kandinski::generate($this->input);
            // Tg::sendPhoto($this->bot->chat_id, $photo, $this->input);

            /*
            $data = Curl::post('https://api.openai.com/v1/images/generations',
            json_encode([
                'prompt' => $this->input,
                'n' => 1,
                'size' => '512x512',
            ]),
            ['headers' => ['Authorization: Bearer '.Config::get('openai_api_key'), 'Content-Type: application/json']]);

            if(isset($data['error']['message'])) {
                return (string)$data['error']['message'];
            }

            if(isset($data['data']) && isset($data['data'][0]['url'])) {
                $photo = (string)$data['data'][0]['url'];
                Tg::sendPhoto($this->bot->chat_id, $photo, $this->input);
            }
            */
            return '';
        }
    }
