<?php

namespace Revobot\Services;

use Revobot\Util\Curl;

class AnswersMailru
{

    private const BASE_GO_URL = 'https://go.mail.ru/answer_json?';
    private const BASE_OTVET_URL = 'https://otvet.mail.ru/api/v2/question?';


    /**
     * @param string $input
     * @return string
     */
    public static function get(string $input): string
    {

        $params = http_build_query([
            'ajax_id' => 5,
            'num' => 1,
            'sf' => 0,
            'zvstate' => 1,
            'q' => $input
        ]);

        $results = Curl::get(self::BASE_GO_URL .$params);


        $questions = json_decode($results, true);

        if(!isset($questions['results']) && !isset($questions['results'][0]['id'])){
            return '';
        }


        $params = http_build_query([
           'qid' =>  (int)$questions['results'][0]['id']
        ]);

        $answers = Curl::get(self::BASE_OTVET_URL . $params);

        $answers = json_decode($answers, true);


        if(!isset($answers['bestanswer'])){
            return '';
        }
        $bestanswer = (int)$answers['bestanswer'];

        $text = "";
        if (!empty($answers)) {
            foreach ($answers as $answer) {
                if (!isset($answer['id'])) {
                    return '';
                }
                if ((int)$answer['id'] == $bestanswer) {
                    $text = strip_tags((string)$answer['atext']);
                    break;
                }
            }

            if ($text === "") {
                $text = strip_tags((string)$answers[0]['atext']);
            }
        } else {
            $text = '';
        }

        return $text;
    }

}