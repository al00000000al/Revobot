<?php

namespace Revobot\Commands;


use Revobot\Util\Curl;

class CalcCmd extends BaseCmd
{

    /**
     * @param $input
     */
    public function __construct($input)
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
