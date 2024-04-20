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

        $results = Curl::get(self::BASE_GO_URL . $params);


        $questions = (array)json_decode($results, true);

        if (!isset($questions['results']) || count($questions['results']) === 0) {
            return '';
        }

        $random_qid = (int) $questions['results'][mt_rand(0, count($questions['results']) - 1)]['id'];

        $params = http_build_query(['qid' =>  $random_qid]);

        $answers = Curl::get(self::BASE_OTVET_URL . $params);

        $answers = (array)json_decode($answers, true);


        if (!isset($answers['bestanswer'])) {
            return '';
        }
        //$bestanswer = (int)$answers['bestanswer'];

        $text = $answers['answers'][mt_rand(0, count($answers['answers']) - 1)]['atext'];
        return (string)strip_tags($text);
    }
}
