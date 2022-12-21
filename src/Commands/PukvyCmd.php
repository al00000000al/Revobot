<?php

namespace Revobot\Commands;

use Revobot\Games\Pukvy;

class PukvyCmd extends BaseCmd
{

    const KEYS = ['pukvy','пуквы',];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'риска миса';

    /**
     * @param $input
     */
    public function __construct($input)
    {
        parent::__construct($input);
        $this->setDescription('Введите /pukvy два слова');
    }

    /**
     * @return string
     */
    public function exec(): string
    {

        if (mb_strlen($this->input) > 0) {
            $words = explode(' ', $this->input);
            if (count($words) < 2 || count($words) > 2) {
                return $this->description;
            }

            return (new Pukvy($this->input))->convert();

        }else{
            return $this->description;
        }


    }
}
