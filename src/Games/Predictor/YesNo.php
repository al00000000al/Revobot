<?php

namespace Revobot\Games\Predictor;

use Revobot\Util\Math;

class YesNo extends PredictBase
{
    private const YES_STR = 'Да';
    private const NO_STR = 'Нет';
    private const MAYBE_STR = 'Может быть';


    /**
     * @return string
     */
    public function calc(): string
    {
        $numbers = $this->wordsToNum();
        $avg = Math::avg($numbers);
        return $this->getString($avg);
    }

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
                $choice = mt_rand(0, 1);
                if ($choice === 0) {
                    $result = self::YES_STR;
                } else {
                    $result = self::NO_STR;
                }
        }
        return $result;
    }

}
