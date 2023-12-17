<?php

    namespace Revobot\Commands;

use Revobot\Revobot;

    class StableDiffusionCmd extends BaseCmd
    {
        private Revobot $bot;
        const KEYS = ['stable','sd','сд'];
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

            if (empty($this->input)){
                return $this->description;
            }
            $user_id = $this->bot->getUserId();
            $chat_id = $this->bot->chat_id;

            $this->bot->pmc
            ->set(self::PMC_KEY.'_'.$user_id, ['chat_id' => $chat_id, 'user_id' => $user_id, 'prompt' => $this->input]);

            return '';
        }
    }
