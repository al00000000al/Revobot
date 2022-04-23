<?php

namespace Revobot\Games\Predictor;

use Revobot\Util\Math;

class When extends PredictBase
{
    protected array $input = [];

    /**
     * @return string
     */
    public function calc(): string
    {
        return "Я думаю это произойдет " .
            date('d-m-Y H:i:s',
                $this->getTime(
                    Math::avg($this->wordsToNum())
                )
            );
    }

    private function getTime(int $avg): int
    {

        $next_time = time();
        switch ($avg) {
            case -1:
                $next_time += 40000000;
                break;
            case 1:
                $next_time += 30000000;
                break;
            default:
                $next_time += 10000000;
                break;
        }

        $new_time_pref = substr(dechex($next_time ), 0, 3);

        $crc = substr(crc32(join("_", $this->input)), 5);
        return (int)hexdec($new_time_pref . $crc);
    }


}