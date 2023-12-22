<?php

namespace Revobot\Commands;

use Revobot\Config;
use Revobot\Revobot;
use Revobot\Util\Curl;

class FoodCmd extends BaseCmd
{
    const KEYS = ['food'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'get food menu';
    const IS_ADMIN_ONLY = true;
    private Revobot $bot;

    public function __construct(string $input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
        $this->setDescription('/food get food menu');
    }

    public function exec(): string
    {
        if (!$this->isAdmin($this->bot->getUserId())) {
            return '';
        }
        $user_options = $this->input;
        $response = (string) Curl::get(
            Config::get('food_api') . '?' .
                http_build_query(['key' => Config::get('food_api_key'), 'rnd' => mt_rand(0, 999999), 'user' => $user_options])
        );

        return $response;
    }
}
