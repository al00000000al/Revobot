<?php

namespace Revobot\Commands;

use Revobot\Util\Curl;
use RuntimeException;
use Revobot\Util\PMC; // предполагается, что класс PMC доступен

class GovdebtCmd extends BaseCmd
{
    const KEYS = ['debt', 'госдолг', 'долг'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = '[количество] топ стран по госдолгу';

    /** @var string Ключ для кэша */
    private const CACHE_KEY = 'govdebt_top';
    /** @var int Время жизни кэша в секундах (6 часов) */
    private const CACHE_TTL = 21600;
    /** @var string Индикатор Всемирного банка: госдолг центрального правительства, текущие доллары США */
    private const WB_INDICATOR = 'GC.DOD.TOTL.CD';
    /** @var int Максимальное количество стран в топе по умолчанию */
    private const DEFAULT_LIMIT = 10;

    public function __construct(string $input)
    {
        $this->setDescription(self::HELP_DESCRIPTION);
        parent::__construct($input);
    }

    public function exec(): string
    {
        $limit = self::DEFAULT_LIMIT;
        if (!empty($this->input) && is_numeric($this->input) && (int)$this->input > 0) {
            $limit = min((int)$this->input, 20);
        }

        try {
            $debtData = $this->getTopDebt($limit);
        } catch (RuntimeException $e) {
            return "❌ Ошибка получения данных: " . $e->getMessage();
        }

        if (empty($debtData)) {
            return "❌ Нет данных по госдолгу стран.";
        }

        return $this->formatOutput($debtData, $limit);
    }

    /**
     * Возвращает топ стран по госдолгу (с кэшированием через PMC).
     */
    private function getTopDebt(int $limit): array
    {
        // Пробуем получить из кэша
        $cached = PMC::get(self::CACHE_KEY);
        if ($cached !== false && is_array($cached)) {
            return array_slice($cached, 0, $limit);
        }

        // Если в кэше пусто — грузим из API
        $data = $this->fetchFromWorldBank();
        if (empty($data)) {
            throw new RuntimeException('Не удалось загрузить данные от Всемирного банка');
        }

        // Сортируем по убыванию долга
        usort($data, fn($a, $b) => $b['debt'] <=> $a['debt']);

        // Сохраняем в кэш (флаги оставляем 0, время жизни в секундах)
        PMC::set(self::CACHE_KEY, $data, 0, self::CACHE_TTL);

        return array_slice($data, 0, $limit);
    }

    /**
     * Запрашивает данные у API Всемирного банка.
     */
    private function fetchFromWorldBank(): array
    {
        $url = 'http://api.worldbank.org/v2/country/all/indicator/' . self::WB_INDICATOR
            . '?format=json&per_page=10000&date=2023:2023';

        $json = Curl::get($url);
        if (!$json) {
            throw new RuntimeException('Сайт Всемирного банка не отвечает');
        }

        $data = json_decode($json, true);
        if (!is_array($data) || !isset($data[1]) || !is_array($data[1])) {
            throw new RuntimeException('Некорректный ответ от API');
        }

        $result = [];
        foreach ($data[1] as $item) {
            if (!isset($item['value']) || !is_numeric($item['value']) || !isset($item['country']['value'])) {
                continue;
            }

            $year = isset($item['date']) ? (int)$item['date'] : 0;
            if ($year < 2015) {
                continue; // слишком старые данные не учитываем
            }

            $country = $item['country']['value'];
            $debt = (float)$item['value'] / 1e9; // в млрд USD

            $result[] = [
                'name' => $country,
                'debt' => $debt,
                'year' => $year
            ];
        }

        if (empty($result)) {
            throw new RuntimeException('Нет данных за последние годы');
        }

        return $result;
    }

    /**
     * Форматирует вывод.
     */
    private function formatOutput(array $data, int $limit): string
    {
        $lines = [];
        $lines[] = "🌍 **Топ-{$limit} стран по госдолгу (в млрд USD)**\n";

        $position = 1;
        foreach ($data as $item) {
            $name = $item['name'];
            if (mb_strlen($name) > 30) {
                $name = mb_substr($name, 0, 27) . '...';
            }
            $debtFormatted = number_format($item['debt'], 2, '.', ' ');
            $year = $item['year'];
            $lines[] = sprintf("%2d. %-30s %12s млрд $ (%d г.)", $position, $name, $debtFormatted, $year);
            $position++;
        }

        $lines[] = "\n_Данные: Всемирный банк (последний доступный год)_";
        return implode("\n", $lines);
    }
}
