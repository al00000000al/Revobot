<?php

namespace Revobot\Games\Predictor;

use Revobot\Numerology\Converter;
use Revobot\Util\Strings;

class PredictBase
{
    protected const YES = 1;
    protected const NO = -1;
    protected const MAYBE = 0;
    /** @var string[]  */
    protected array $input = [];

    /**
     * @param string $input
     */
    public function __construct(string $input)
    {
        $words = Strings::stringToWords($input);

        $this->input = $words;
    }



    /**
     * @return int[]
     */
    protected function wordsToNum(): array
    { 
        $result = [];
        $inp = $this->input;
        foreach ($inp as $word) {
            $num = (int)Converter::toNumber($word);
            $result[] = $num;
        }
        return $result;
    }
}
