<?php

namespace Revobot\Commands;

use Revobot\Util\Hash;

class OrCmd extends BaseCmd
{
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

        /** @var string[] $words_valid */
        $words_valid = [];

        for($i = 0; $i < $count_words; $i++){
            $hash = crc32(Hash::generate($words[$i]));
            if($hash % $count_words === 0){
                $words_valid = [$words[$i]];
            }
        }

        if(empty($words_valid)){
            return 'Ничего из перечисленного не подходит';
        }

        return implode(' ', $words_valid);
    }
}