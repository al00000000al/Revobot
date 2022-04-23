<?php

namespace Revobot\Games\Predictor;

use Revobot\Numerology\Converter;
use Revobot\Util\Math;
use Revobot\Util\Strings;

class PredictBase
{
    protected const YES = 1;
    protected const NO = -1;
    protected const MAYBE = 0;
    protected array $input = [];

    public function __construct($input)
    {
        $this->input = Strings::stringToWords($input);
    }

    /**
     * @return string
     */
    public function calc(): string
    {
        return Math::avg($this->wordsToNum());
    }

    /**
     * @return array
     */
    protected function wordsToNum(): array
    {
        $result = [];
        foreach ($this->input as $word) {
            $result[] = Converter::toNumber($word);
        }
        return $result;
    }
}
