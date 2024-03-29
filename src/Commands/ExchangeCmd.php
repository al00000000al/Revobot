<?php

namespace Revobot\Commands;

use Revobot\Util\Curl;
use Revobot\Util\Strings;

class ExchangeCmd extends BaseCmd
{
    const KEYS = ['exchange', 'currency', 'курс'];
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

        list($ammount_string, $currency) = Strings::parseTwoCommands($this->input);
        $amount = (float)$ammount_string;
        if (empty($currency)) {
            return $this->description;
        }

        $date = date('d/m/Y');

        // URL API Центрального Банка России
        $apiUrl = 'https://www.cbr.ru/scripts/XML_daily.asp?date_req=' . $date;

        $res = Curl::get($apiUrl);

        // Использование регулярного выражения для извлечения курса
        $pattern = '/<CharCode>' . $currency . '<\/CharCode>.*?<Value>(.*?)<\/Value>/s';
        if (preg_match($pattern, $res, $matches)) {
            $value = str_replace(',', '.', $matches[1]);
            $rate = floatval($value);
        } else {
            return 'Не удалось получить курс валют';
        }

        $result = $amount * $rate;

        return $amount . ' ' . $currency . ' = ' . $result . ' RUB';
    }
}
