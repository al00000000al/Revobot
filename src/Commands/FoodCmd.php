<?php

    namespace Revobot\Commands;

    use Revobot\Config;
use Revobot\Util\Curl;

    class FoodCmd extends BaseCmd
    {
        const KEYS = ['food'];
        const IS_ENABLED = true;
        const HELP_DESCRIPTION = 'get food menu';
        const IS_ADMIN_ONLY = true;

        public function __construct(string $input)
        {
            parent::__construct($input);
            $this->setDescription('/food get food menu');
        }

        public function exec(): string
        {
           $response = (string) Curl::get(Config::get('food_api').'?key='.Config::get('food_api_key'));
           return $response;
        }
    }
