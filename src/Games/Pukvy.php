<?php

namespace Revobot\Games;

use Revobot\Util\Strings;

class Pukvy
{
    private const RUS_V = ['а', 'е', 'ё', 'и', 'о', 'у', 'ы', 'э', 'ю', 'я'];
    private const RUS_N = [
        'б', 'в', 'г', 'д', 'ж', 'з', 'к', 'л', 'м', 'н',
        'п', 'р', 'с', 'т', 'ф', 'х', 'ц', 'ч', 'ш', 'щ'
    ];

    private array $words = [];

    /**
     * @param string $input
     */
    public function __construct(string $input)
    {
        $input = iconv('cp1251', 'UTF-8', $input);
        $words = Strings::stringToWords($input);
        $this->words[0] = Strings::cyrillicOnly($words[0]);
        $this->words[1] = Strings::cyrillicOnly($words[1]);

    }

    /**
     * @return string
     */
    public function convert(): string
    {
        $first_1 = mb_substr($this->words[0], 0, 1);
        $first_2 = mb_substr($this->words[1], 0, 1);

        $second_1 = mb_substr($this->words[0], 1, 1);
        $second_2 = mb_substr($this->words[1], 1, 1);

        if
        (
            (in_array($second_1, self::RUS_V) && (in_array($first_1, self::RUS_V))
                || (in_array($second_2, self::RUS_V) && (in_array($first_2, self::RUS_V)))
            ) ||
            (in_array($second_1, self::RUS_N) && (in_array($first_1, self::RUS_N))
                || (in_array($second_2, self::RUS_N) && (in_array($first_2, self::RUS_N)))
            )
        ) {

            $slog_1 = $this->slogi($this->words[0])[0];
            $slog_2 = $this->slogi($this->words[1])[0];

            $word_1 = $slog_2 . mb_substr($this->words[0], mb_strlen($slog_1));
            $word_2 = $slog_1 . mb_substr($this->words[1], mb_strlen($slog_2));

        } else {
            $word_1 = $first_2 . mb_substr($this->words[0], 1);
            $word_2 = $first_1 . mb_substr($this->words[1], 1);
        }
        return $word_1 . ' ' . $word_2;
    }

    /**
     * @param $text
     * @return false|string[]|force(string[])
     */
    private function slogi($text)
    {

        $RusA = "[абвгдеёжзийклмнопрстуфхцчшщъыьэюя]";
        $RusV = "[аеёиоуыэюя]";
        $RusN = "[бвгджзклмнпрстфхцчшщ]";
        $RusX = "[йъь]";

        $regs = [];

        $regs[] = "~(" . $RusX . ")(" . $RusA . $RusA . ")~iu";
        $regs[] = "~(" . $RusV . ")(" . $RusV . $RusA . ")~iu";
        $regs[] = "~(" . $RusV . $RusN . ")(" . $RusN . $RusV . ")~iu";
        $regs[] = "~(" . $RusN . $RusV . ")(" . $RusN . $RusV . ")~iu";
        $regs[] = "~(" . $RusV . $RusN . ")(" . $RusN . $RusN . $RusV . ")~iu";
        $regs[] = "~(" . $RusV . $RusN . $RusN . ")(" . $RusN . $RusN . $RusV . ")~iu";
        $regs[] = "~(" . $RusX . ")(" . $RusA . $RusA . ")~iu";
        $regs[] = "~(" . $RusV . ")(" . $RusA . $RusV . ")~iu";


        foreach ($regs as $cur_regxp) {
            $text = preg_replace($cur_regxp, "$1-$2", $text);
        }
        return explode('-', $text);

    }

}