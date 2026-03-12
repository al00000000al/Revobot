<?php

/**
 * Класс PentabaseLogic
 * Чистая реализация Октанализа и Пентабазиса по тексту.
 */

namespace Revobot\Numerology;

class PentabaseLogic
{
    // Описания инстанций
    private array $instances = [
        5 => ['name' => 'Душа', 'role' => 'Патриот/Служение', 'value' => 'Страна'],
        4 => ['name' => 'Сердце', 'role' => 'Управленец/Лидер', 'value' => 'Государство'],
        3 => ['name' => 'Иррацио', 'role' => 'Творец/Креатор', 'value' => 'Человек'],
        2 => ['name' => 'Рацио', 'role' => 'Законник/Консерватор', 'value' => 'Семья'],
        1 => ['name' => 'Разум', 'role' => 'Аналитик/Эгоист', 'value' => 'Общество']
    ];

    /**
     * Вычисляет код и формирует отчет
     */
    public function analyze(string $dateString): array
    {
        $time = strtotime($dateString);
        if (!$time) return ['error' => 'Неверная дата'];

        $day   = (int)date('d', $time);
        $month = (int)date('m', $time);
        $year  = (int)date('Y', $time);

        // 1. Фоновый доминант года (1 балл)
        $lastDigit = $year % 10;
        $bgMap = [0 => 1, 1 => 1, 2 => 5, 3 => 5, 4 => 4, 5 => 4, 6 => 3, 7 => 3, 8 => 2, 9 => 2];
        $bgId = $bgMap[$lastDigit];

        // 2. Западный доминант (2 + 1 балл)
        $zCode = $this->getZodiacCode($month, $day);

        // 3. Восточный доминант (2 + 1 балл)
        $oCode = $this->getOrientalCode($year);

        // Считаем итоговые баллы
        $scores = [5 => 0, 4 => 0, 3 => 0, 2 => 0, 1 => 0];

        // Добавляем баллы знаков
        $this->addPoints($scores, $zCode);
        $this->addPoints($scores, $oCode);

        // Добавляем фоновый балл
        $scores[$bgId] += 1;

        return $this->buildResponse($scores, $bgId, $dateString);
    }

    private function addPoints(array &$scores, string $code): void
    {
        $scores[(int)$code[0]] += 2; // Первый доминант
        $scores[(int)$code[1]] += 1; // Второй доминант
    }

    private function getZodiacCode(int $m, int $d): string
    {
        $date = $m * 100 + $d;
        if ($date >= 120 && $date <= 219) return "14"; // Водолей
        if ($date >= 220 && $date <= 320) return "43"; // Рыбы
        if ($date >= 321 && $date <= 419) return "32"; // Овен
        if ($date >= 420 && $date <= 520) return "23"; // Телец
        if ($date >= 521 && $date <= 621) return "13"; // Близнецы
        if ($date >= 622 && $date <= 722) return "42"; // Рак
        if ($date >= 723 && $date <= 822) return "31"; // Лев
        if ($date >= 823 && $date <= 922) return "21"; // Дева
        if ($date >= 923 && $date <= 1023) return "12"; // Весы
        if ($date >= 1024 && $date <= 1122) return "41"; // Скорпион
        if ($date >= 1123 && $date <= 1221) return "34"; // Стрелец
        return "24"; // Козерог
    }

    private function getOrientalCode(int $year): string
    {
        $codes = ["14", "43", "32", "23", "13", "42", "31", "21", "12", "41", "34", "24"];
        // 1900 — год Крысы (14)
        return $codes[($year - 1900) % 12];
    }

    private function buildResponse(array $scores, int $bgId, string $date): array
    {
        arsort($scores);

        $topId = (int)array_key_first($scores);

        return [
            'date'   => $date,
            'code'   => "{$scores[5]}{$scores[4]}{$scores[3]}-{$scores[2]}{$scores[1]}",
            'main'   => $this->instances[$topId],
            'base'   => $this->instances[$bgId],
            'scores' => $scores,
            'advice' => $this->getAdvice($topId)
        ];
    }

    private function getAdvice(int $id): string
    {
        $advices = [
            5 => "Ваша миссия — служение. Ищите себя в делах, приносящих пользу стране и людям.",
            4 => "Ваша сила — в ответственности. Вы рождены укреплять государство и вести за собой.",
            3 => "Ваша природа — созидание. Реализуйтесь через творчество и новые идеи.",
            2 => "Ваша опора — традиции. Вы лучший в сохранении ценностей семьи и порядка.",
            1 => "Ваш инструмент — разум. Помогайте обществу находить согласие через интеллект."
        ];
        return $advices[$id];
    }
}
