<?php

namespace Revobot\Services;

use Revobot\Util\Curl;

class AnswersMailru
{

    private const BASE_GO_URL = 'https://go.mail.ru/answer_json?';
    private const BASE_OTVET_URL = 'https://otvet.mail.ru/api/topic/answers/';


    /**
     * @param string $input
     * @return string
     */
    public static function get(string $input): string
    {

        $questions = self::searchQuestions($input);

        if (!isset($questions['results']) || count($questions['results']) === 0) {
            return '';
        }

        $random_qid = (int) $questions['results'][mt_rand(0, count($questions['results']))]['id'];

        $answers = self::getAnswers($random_qid);
        $text = self::getRandomAnswerText($answers);
        return (string)strip_tags($text);
    }

    public static function searchQuestions(string $text): array
    {
        $results = (string) Curl::get(self::BASE_GO_URL . http_build_query([
            'ajax_id' => 5,
            'num' => 1,
            'sf' => 0,
            'zvstate' => 1,
            'q' => $text
        ]));
        return (array)json_decode($results, true);
    }

    public static function getAnswers(int $qid): array
    {
        $answers = Curl::get(self::BASE_OTVET_URL . $qid . '?limit=1000&dir=0&sort=rating&order_by=desc');
        return (array)json_decode($answers, true);
    }

    public static function getRandomAnswerText(array $answers): string
    {
        $replies_cnt = count($answers['result']['replies']);

        $text = $answers['result']['replies'][mt_rand(0, $replies_cnt) - 1]['content']['content'][0]['content'][0]['text'];
        return (string)$text;
    }
}
