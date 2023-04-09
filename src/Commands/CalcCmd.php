<?php

namespace Revobot\Commands;


use Revobot\Util\Curl;

class CalcCmd extends BaseCmd
{

    const KEYS = ['calc','калк'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'Калькулятор';


    public function __construct(string $input)
    {
        parent::__construct($input);
        $this->setDescription('Введите /calc [выражение]');
    }


    /**
     * @return string
     */
    public function exec(): string
    {
        $url = 'https://api.mathjs.org/v4/?expr=' . urlencode($this->input);
        return (string)Curl::get($url);

    }
}
