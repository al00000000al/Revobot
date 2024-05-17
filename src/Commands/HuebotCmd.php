<?php

namespace Revobot\Commands;

use Revobot\Util\Strings;

class HuebotCmd extends BaseCmd
{
    const KEYS = ['huebot', 'хуеслово', 'slovo', 'hueslovo'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'напишите слово и получите х*еслово';

    public function __construct(string $input)
    {
        parent::__construct($input);
        $this->setDescription('/slovo напишите слово и получите х*еслово');
    }

    public function exec(): string
    {
        if (empty($this->input)) {
            return $this->description;
        }

        return $this->process_words($this->input);
    }


    // Функция для разбивки строки на символы UTF-8
    private function split_utf8_string(string $str): array
    {
        $strlen = mb_strlen($str, 'UTF-8');
        return array_map(fn ($i) => mb_substr($str, $i, 1, 'UTF-8'), range(0, $strlen - 1));
    }

    // Функция для изменения гласной буквы
    private function transform_vowel(string $vowel): string
    {
        $transformations = [
            'о' => 'ё', 'О' => 'ё', 'а' => 'я', 'А' => 'я', 'у' => 'ю', 'У' => 'ю', 'э' => 'е', 'Э' => 'е'
        ];
        return $transformations[$vowel] ?? $vowel;
    }

    // Функция для удаления части слова до первой гласной буквы
    private function remove_until_vowel(string $word): string
    {
        $vowels = ["а", "е", "ё", "и", "о", "у", "ы", "э", "ю", "я", "А", "Е", "Ё", "И", "О", "У", "Ы", "Э", "Ю", "Я", "a", "e", "i", "o", "u", "A", "E", "I", "O", "U"];
        $chars = $this->split_utf8_string($word);
        $new_word = "";
        $vowel_found = false;

        foreach ($chars as $char) {
            if (!$vowel_found && in_array($char, $vowels, true)) {
                $char = $this->transform_vowel($char);
                $vowel_found = true;
            }

            if ($vowel_found) {
                $new_word .= $char;
            }
        }

        return $new_word;
    }

    // Функция для получения последней буквы каждого слова
    private function get_last_letter(string $str): string
    {
        return mb_substr($str, -1, 1, 'UTF-8');
    }

    // Основная функция
    private function create_new_word(string $input_word): string
    {
        $modified_word = $this->remove_until_vowel($input_word);
        if (mb_strlen($modified_word, 'UTF-8') < 1) {
            $modified_word = "е" . $this->get_last_letter($input_word);
        }
        return "ху" . $modified_word;
    }

    private function process_words(string $input_string, string $separator = " "): string
    {
        $words = preg_split('/' . preg_quote($separator, '/') . '/', $input_string);
        $processed_words = [];
        foreach ($words as $word) {
            $processed_words[] = $this->create_new_word((string)$word);
        }
        return implode($separator, $processed_words);
    }



    private function process(string $input): string
    {
        $words = Strings::stringToWords($input);
        $response = [];
        foreach ($words as $word) {
            $parts = Strings::splitIntoSyllables($word);
            if (count($parts) > 1) {
                if (Strings::substr($parts[0], -1, 1) === 'и') {
                    $pref = 'и';
                } else  if (Strings::substr($parts[0], -1, 1) === 'а') {
                    $pref = 'я';
                } else  if (Strings::substr($parts[0], -1, 1) === 'ю') {
                    $pref = 'ю';
                } else  if (Strings::substr($parts[0], -1, 1) === 'о') {
                    $pref = 'ё';
                } else {
                    $pref = 'е';
                }
                $response[] = 'ху' . $pref . (string)array_pop($parts);
            } else {
                if (Strings::isVowelLetter($parts[0], 0)) {
                    $response[] = 'хуй' . Strings::substr($parts[0], 1, strlen($parts[0]) - 1);
                } else {
                    $response[] = 'хуй' . $parts[0];
                }
            }
        }

        return implode(' ', $response);
    }
}
