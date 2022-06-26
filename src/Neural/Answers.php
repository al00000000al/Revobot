<?php

namespace Revobot\Neural;

use Revobot\Services\AnswersMailru;
use Revobot\Services\DobroAI;
use Revobot\Services\Huggingface\SbermGPT;
use Revobot\Services\SberGPT3;

class Answers
{

    /**
     * @param string $input
     * @return string
     */
    public static function getAnswer(string $input): string
    {

        try {
            $text = DobroAI::get($input);
        } catch (\Exception $e) {

            $s = mt_rand(0, 100);
            if ($s < 0) {
                $text = SberGPT3::generate($input);
            } else {
                $text = AnswersMailru::get($input);
            }
        }


        return $text;

    }


    /**
     * @param string $message
     * @param string $history
     * @return string
     */
    public static function formatHistory(string $message, string $history): string
    {
        $message_new = substr($message, 0, 200);

        if ($history == null) {
            $history = '';
        } else {
            $history_arr = explode("\n", $history);
            array_shift($history_arr);
            $history_arr = array_slice($history_arr, -5, 5, true);
            $history = implode(PHP_EOL, $history_arr);
        }

        $user_msg = explode("\n", $message_new)[0];
        if ($user_msg !== '«»' && !empty($user_msg)) {
            $history .= '- ' . 'Юзер' . ': "' . $user_msg . '"' . PHP_EOL;
        }
        $history .= '- Револиса: "';
        return $history;

    }

    /**
     * @param string $result
     * @return string
     */
    public static function formatResult(string $result): string
    {
        $result = trim(explode("\"", $result)[0]);
        $result = str_replace(['»', '«'], '', $result);
        return $result;
    }
}
