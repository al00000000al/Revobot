<?php

    namespace Revobot\Commands;

    class ExecuteCmd extends BaseCmd
    {
        const KEYS = ['execute'];
        const IS_ENABLED = true;
        const HELP_DESCRIPTION = 'lua script';

        public function __construct(string $input)
        {
            parent::__construct($input);
            $this->setDescription('/execute run lua script');
        }

        public function exec(): string
        {
            if (empty($this->input)){
                return $this->description;
            }
            return $this->input;
        }
    }