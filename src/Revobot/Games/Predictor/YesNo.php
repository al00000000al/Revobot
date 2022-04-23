<?php

namespace Revobot\Games\Predictor;

use Revobot\Util\Math;

class YesNo extends PredictBase
{
    private const YES_STR = 'Да';
    private const NO_STR = 'Нет';
    private const MAYBE_STR = 'Может быть';

    protected array $input = [];

    /**
     * @return string
     */
    public function calc(): string
    {
        return self::getString(Math::avg($this->wordsToNum()));
    }

    /**
     * @param $avg_rate
     * @return string
     */
    private function getString($avg_rate): string
    {
        switch ($avg_rate) {
            case self::YES:
                $result = self::YES_STR;
                break;
            case self::NO:
                $result = self::NO_STR;
                break;
            default:
                $result = self::MAYBE_STR;
        }
        return $result;
    }

}