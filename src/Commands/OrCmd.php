<?php

namespace Revobot\Commands;

use Revobot\Config;
use Revobot\Services\OpenAIService;
use Revobot\Util\Hash;

class OrCmd extends BaseCmd
{
    const KEYS = ['or', 'ili', 'или',];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'Выбрать что-то одно';
    public function __construct(string $input)
    {
        parent::__construct($input);
        $this->setDescription('/or first second');
    }

    public function exec(): string
    {
        if (empty($this->input)) {
            return $this->description;
        }

        $words = explode(' ', $this->input);
        $count_words = count($words);
        if ($count_words < 2) {
            return $this->description;
        }

        if ((bool) Config::getInt('use_ai_cmd')) {
            list($_, $answer) =  OpenAIService::generate(implode(',', $words), "Выбери что-то одно из этого списка слов и напиши только это слово в ответе и все", []);
            return (string)$answer;
        }

        /** @var string[] $words_valid */
        $words_valid = [];

        for ($i = 0; $i < $count_words; $i++) {
            $hash = crc32(Hash::generate($words[$i]));
            if ($hash % $count_words === 0) {
                $words_valid = [$words[$i]];
            }
        }

        if (empty($words_valid)) {
            return 'Ничего из перечисленного не подходит';
        }

        return implode(' ', $words_valid);
    }
}
