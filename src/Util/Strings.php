<?php

namespace Revobot\Util;

class Strings
{

    /**
     * @param string $string
     * @return string[]
     */
    public static function stringToWords(string $string): array
    {
        return explode(" ", $string);
    }

    public static function cyrillicOnly(string $string): string
    {
        return (string) preg_replace('/[^а-яА-ЯёЁ]/ui', '', $string);
    }

    /**
     * @param int $length
     * @param string $characters
     * @return string
     */
    public static function random(
        int $length = 10,
        string $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'
    ): string {
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public static function xor(string $a, string $b)
    {
        $result = '';
        for ($i = 0; $i < strlen($a); $i++) {
            $result .= dechex(hexdec($a[$i]) ^ hexdec($b[$i]));
        }
        return $result;
    }

    public static function parseCommandArguments($argsString)
    {
        $pattern = '/--([\w\d\_]+)\s+([^\s]+)/';
        preg_match_all($pattern, $argsString, $matches);

        $args = [];
        if (!empty($matches[1])) {
            foreach ($matches[1] as $index => $key) {
                $args[$key] = $matches[2][$index] ?? null;
            }
        }

        return $args;
    }

    public static function cleanCommandArguments($original_string)
    {
        $pattern = '/--\S+?\s*/';
        $cleaned_string = preg_replace($pattern, '', $original_string);
        return $cleaned_string;
    }

    public static function img2base64($path)
    {
        if (!file_exists($path)) {
            return '';
        }
        $data = file_get_contents($path);
        return 'data:image/jpg;base64,' . base64_encode($data);
    }

    public static function transliterate($text)
    {
        $text = strtolower($text);
        $transliterationTable = [
            'а' => 'a', 'б' => 'b', 'в' => 'v',
            'г' => 'g', 'д' => 'd', 'е' => 'e',
            'ё' => 'e', 'ж' => 'zh', 'з' => 'z',
            'и' => 'i', 'й' => 'y', 'к' => 'k',
            'л' => 'l', 'м' => 'm', 'н' => 'n',
            'о' => 'o', 'п' => 'p', 'р' => 'r',
            'с' => 's', 'т' => 't', 'у' => 'u',
            'ф' => 'f', 'х' => 'h', 'ц' => 'ts',
            'ч' => 'ch', 'ш' => 'sh', 'щ' => 'shch',
            'ъ' => '', 'ы' => 'y', 'ь' => '',
            'э' => 'e', 'ю' => 'yu', 'я' => 'ya'
        ];

        return strtr($text, $transliterationTable);
    }


    public static function parseSubCommand(string $input)
    {
        $parts = explode(' ', $input, 2);

        if (count($parts) == 2) {
            $command = $parts[0];
            $data = $parts[1];
        } else {
            $command = $input;
            $data = '';
        }

        return tuple($command, $data);
    }

    public static function parseTwoCommands(string $input)
    {
        $parts = explode(' ', $input);

        if (isset($parts[1])) {
            return tuple($parts[0], $parts[1]);
        }
        return tuple($parts[0], '');
    }

    /**
     * Разбивает слово на слоги
     *
     * @see https://habr.com/ru/articles/739026/
     */
    public static function splitIntoSyllables(string $word): array
    {
        $vowelsSet = ('аеёиоуыэюя');  // Набор гласных букв
        $specialsSet = ('йьъ');  // Набор специальных символов

        $prevVowel = strlen($word);
        for ($i = 0; $i < strlen($word); $i++) {
            if (strpos($vowelsSet, mb_substr($word, $i, 1, 'UTF-8')) !== false) {
                $prevVowel = $i;
                break;
            }
        }

        $pos = 0;
        $syllables = [];
        for ($i = $prevVowel + 1; $i < strlen($word); $i++) {
            if (strpos($vowelsSet, mb_substr($word, $i, 1, 'UTF-8')) !== false) {
                $a = $prevVowel;
                $b = $i;
                $npos = 0;
                for ($j = $b - 1; $j > $a; $j--) {
                    if (strpos($specialsSet, mb_substr($word, $j, 1, 'UTF-8')) !== false) {
                        $npos = $j + 1;
                        break;
                    }
                }
                if ($npos === 0) {
                    if ($b - $a <= 2) {
                        $npos = $a + 1;
                    } elseif ($b - $a >= 6) {
                        $npos = $b - 3;
                    } else {
                        $npos = $a + 2;
                    }
                }
                $syllables[] = mb_substr($word, $pos, $npos - $pos, 'UTF-8');
                $pos = $npos;
                $prevVowel = $i;
            }
        }
        $syllables[] = mb_substr($word, $pos, strlen($word) - $pos, 'UTF-8');  // Добавляем оставшуюся часть слова в массив

        return $syllables;
    }

    /**
     * Является ли буква гласной в позиции в слове (позиция -1 для последней буквы)
     */
    public static function isVowelLetter(string $word, int $position = 0): bool
    {
        return strpos('аеёиоуыэюя',  mb_substr($word, $position, 1, 'UTF-8')) !== false;
    }

    /**
     * Является ли буква согласной в позиции в слове (позиция -1 для последней буквы)
     */
    public static function isConsonantLetter(string $word, int $position = 0): bool
    {
        return strpos('бвгджзйклмнпрстфхцчшщ', mb_substr($word, $position, 1, 'UTF-8')) !== false;
    }
}
