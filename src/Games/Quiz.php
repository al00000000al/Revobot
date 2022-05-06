<?php

namespace Revobot\Games;

use Revobot\Money\Revocoin;
use Revobot\Revobot;
use Revobot\Services\FCQuestions;

class Quiz
{
    private Revobot $bot;

    private const PMC_QUESTIONS_KEY = 'quiz_questions';
    private const PMC_QUESTION_CURRENT_KEY = 'quiz_question_current_';//provider chat id

    public const QUIZ_WIN_PRIZE = 3;
    public const QUIZ_LOSE_PRIZE = 1;

    public function __construct(Revobot $bot)
    {
        $this->bot = $bot;
    }

    /**
     * @return mixed
     */
    public function getQuestion()
    {
        $current = $this->bot->pmc->get(self::PMC_QUESTION_CURRENT_KEY . $this->bot->provider . $this->bot->chat_id);
        if (!$current) {
            $result = $this->bot->pmc->get(self::PMC_QUESTIONS_KEY);
            if (!$result) {
                $result = FCQuestions::get();
            }
            $current = array_shift($result);
            $this->bot->pmc->set(self::PMC_QUESTIONS_KEY, $result);
            $this->bot->pmc->set(self::PMC_QUESTION_CURRENT_KEY . $this->bot->provider . $this->bot->chat_id, $current);
        }

        return $current;
    }

    public function sendAnswer($answer)
    {
        $question = $this->getQuestion();
        if ((string)$question['answer'] === $answer) {
            $this->bot->pmc->delete(self::PMC_QUESTION_CURRENT_KEY . $this->bot->provider . $this->bot->chat_id);
            (new Revocoin($this->bot))->transaction(self::QUIZ_WIN_PRIZE, $this->bot->getUserId(), -TG_BOT_ID);
            $price = self::QUIZ_WIN_PRIZE;
            $this->bot->sendMessageTg("+" . $price . 'R у ' . $this->bot->getUserNick());
            $this->bot->sendMessageTg((string)$this->getQuestion()['question']);

        } else {
            $commission = self::QUIZ_LOSE_PRIZE * Revocoin::TRANSACTION_COMMISSION;
            $price = self::QUIZ_LOSE_PRIZE - $commission;
            (new Revocoin($this->bot))->transaction(self::QUIZ_LOSE_PRIZE, -TG_BOT_ID, $this->bot->getUserId());
            $this->bot->sendMessageTg("-" .$price . 'R у ' . $this->bot->getUserNick());
        }
    }


}