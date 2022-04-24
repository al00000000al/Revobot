<?php

namespace Revobot\Commands;


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
      return mt_rand(0, 100)."";
    }
}
