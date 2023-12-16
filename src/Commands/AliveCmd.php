<?php

    namespace Revobot\Commands;

    use Revobot\Revobot;

    class AliveCmd extends BaseCmd
    {
        private Revobot $bot;

        const KEYS = ['alive','алив'];
        const IS_ENABLED = true;
        const HELP_DESCRIPTION = 'Состояние бота';

        public function __construct(string $input, Revobot $bot) {
            parent::__construct($input);
            $this->bot = $bot;
        }

        /**
         * @return string
         */
        public function exec(): string {
            $pmc_v = $this->bot->pmc->getVersion();
            return "Жив! PMC: $pmc_v, Bot build: 162";
        }
    }