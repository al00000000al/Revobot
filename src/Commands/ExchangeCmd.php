<?php

namespace Revobot\Commands;

use Revobot\Util\Curl;
use Revobot\Util\Strings;
use RuntimeException;

class ExchangeCmd extends BaseCmd
{
    const KEYS = ['exchange', 'currency', 'курс'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = '/exchange сумма валюта — конвертация в рубли по курсу ЦБ РФ';

    /** @var array|null */
    private static $ratesCache = null;

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

        list($amountStr, $currency) = Strings::parseTwoCommands($this->input);
        $currency = mb_strtoupper(trim($currency));

        if (!is_numeric($amountStr) || $amountStr <= 0) {
            return "❌ Сумма должна быть положительным числом.\n{$this->description}";
        }
        if (empty($currency)) {
            return "❌ Не указана валюта.\n{$this->description}";
        }

        $amount = (float)$amountStr;

        if ($currency === 'RUB') {
            return "{$amount} RUB = {$amount} RUB";
        }

        try {
            $rate = $this->getRate($currency);
        } catch (RuntimeException $e) {
            return "❌ Ошибка получения курса: " . $e->getMessage();
        }

        $result = $amount * $rate;
        return sprintf("%s %s = %.2f RUB", $amount, $currency, $result);
    }

    /**
     * Возвращает курс валюты к рублю.
     *
     * @param string $currencyCode Код валюты (USD, EUR и т.д.)
     * @return float
     * @throws RuntimeException
     */
    private function getRate(string $currencyCode): float
    {
        if (self::$ratesCache === null) {
            self::$ratesCache = $this->fetchTodayRates();
        }

        if (!isset(self::$ratesCache[$currencyCode])) {
            throw new RuntimeException("Валюта {$currencyCode} не найдена в справочнике ЦБ РФ");
        }

        return self::$ratesCache[$currencyCode];
    }

    /**
     * Загружает курсы валют с сайта ЦБ РФ.
     *
     * @return array [код валюты => курс RUB за 1 единицу]
     * @throws RuntimeException
     */
    private function fetchTodayRates(): array
    {
        $date = date('d/m/Y');
        $url = 'https://www.cbr.ru/scripts/XML_daily.asp?date_req=' . $date;

        $xmlString = Curl::get($url);
        if (!$xmlString) {
            throw new RuntimeException('Не удалось загрузить данные с сайта ЦБ РФ');
        }

        $rates = [];

        // Разделяем XML по открывающему тегу <Valute>
        $blocks = explode('<Valute', $xmlString);
        // Первый элемент — это всё, что до первого <Valute (заголовок), его пропускаем
        array_shift($blocks);

        foreach ($blocks as $block) {
            // Из каждого блока извлекаем нужные поля
            if (preg_match('/<CharCode>(.*?)<\/CharCode>/', $block, $codeMatch)
                && preg_match('/<Nominal>(.*?)<\/Nominal>/', $block, $nomMatch)
                && preg_match('/<Value>(.*?)<\/Value>/', $block, $valMatch)) {

                $code = trim($codeMatch[1]);
                $nominal = (int)trim($nomMatch[1]);
                $value = str_replace(',', '.', trim($valMatch[1]));
                $rate = floatval($value) / $nominal;
                $rates[$code] = $rate;
            }
        }

        if (empty($rates)) {
            // Если не удалось найти ни одной валюты, пробуем старый метод (для одной валюты) как fallback
            // Но лучше сразу выбросить исключение с подробностями
            throw new RuntimeException('Не удалось распознать ни одной валюты в ответе ЦБ. Возможно, изменился формат данных.');
        }

        return $rates;
    }
}
