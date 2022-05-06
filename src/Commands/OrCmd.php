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
        if (count($words) < 2) {
            return $this->description;
        }

        $first = $words[0];
        if (count($words) == 3) {
            $second = $words[2];
        } else {
            $second = $words[1];
        }

        $hash1 = crc32($first);
        $hash2 = crc32($second);

        if ($hash1 % 2 === 0) {
            return (string)$first;
        }

        if ($hash2 % 2 === 0) {
            return (string)$second;
        }

        return 'Любое';
    }
}