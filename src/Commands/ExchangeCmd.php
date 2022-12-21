<?php


namespace Revobot\Commands;


use Revobot\Util\Curl;

class ExchangeCmd extends BaseCmd
{

    const KEYS = ['exchange','currency','курс',];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'Курс';

    public function __construct(string $input)
    {
        $this->setDescription('/exchange сумма валюта');
        parent::__construct($input);
    }

    public function exec(): string
    {
        if (empty($this->input)) {
            return $this->description;
        }
        $input = explode(' ', $this->input);

        if(count($input) != 2){
            return $this->description;
        }

        $amount = (float)$input[0];
        $currency = (string)$input[1];

        $params = http_build_query([
            'from' => $currency,
            'to' => 'RUB',
        ]);

        $res = Curl::get('https://api.exchangerate.host/convert?' . $params);
        $out = (array)json_decode($res, true);

        if (!isset($out['result'])) {
            return 'Не удалось получить курс валют';
        }

        $result = $amount * (float)$out['result'];

        return $amount . ' ' . $currency . ' - ' . $result . 'руб.';
    }


}
