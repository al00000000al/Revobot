<?php

namespace Revobot\Games\Predictor;

use Revobot\Util\Math;

class Percents extends PredictBase
{

    /**
     * @return string
     */
    public function calc(): string
    {
        return "Вероятность события: " . $this->getPercents(Math::avg($this->wordsToNum())) . '%';
    }

    /**
     * @param int $avg_rate
     * @return int
     */
    private function getPercents(int $avg_rate): int
    {
        switch ($avg_rate) {
            case self::YES:
                $result = mt_rand(70, 100);
                break;
            case self::NO:
                $result = mt_rand(0, 30);
                break;
            default:
                $result = mt_rand(30, 70);
        }
        return $result;
    }
}
