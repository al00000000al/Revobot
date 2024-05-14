<?php

namespace Revobot\Games;

use Revobot\Money\Revocoin;
use Revobot\Revobot;
use Revobot\Services\FCQuestions;
use Revobot\Util\PMC;

class Quiz
{
    private Revobot $bot;

    private const PMC_QUESTIONS_KEY = 'quiz_questions';
    private const PMC_QUESTION_CURRENT_KEY = 'quiz_question_current_'; //provider chat id

    public const QUIZ_WIN_PRIZE = 5;
    public const QUIZ_LOSE_PRIZE = 2;

    public function __construct(Revobot $bot)
    {
        $this->bot = $bot;
    }

    /**
     * @return mixed
     */
    public function getQuestion()
    {
        $current = PMC::get(self::PMC_QUESTION_CURRENT_KEY . $this->bot->provider . chatId());
        if (!$current) {
            $result = PMC::get(self::PMC_QUESTIONS_KEY);
            if (!$result) {
                $result = FCQuestions::get();
            }
            $current = array_shift($result);
            PMC::set(self::PMC_QUESTIONS_KEY, $result);
            PMC::set(self::PMC_QUESTION_CURRENT_KEY . $this->bot->provider . chatId(), $current);
        }

        return $current;
    }

    public function sendAnswer($answer)
    {
        $question = $this->getQuestion();
        if ((string)$question['answer'] === $answer) {
            PMC::delete(self::PMC_QUESTION_CURRENT_KEY . $this->bot->provider . chatId());
            (new Revocoin($this->bot))->transaction(self::QUIZ_WIN_PRIZE, userId(), $this->bot->getBotId());
            $price = self::QUIZ_WIN_PRIZE;
            $this->bot->sendMessage("+" . $price . 'R у ' . $this->bot->getUserNick());
            $this->bot->sendMessage((string)$this->getQuestion()['question']);
        } else {
            $commission = self::QUIZ_LOSE_PRIZE * Revocoin::TRANSACTION_COMMISSION;
            $price = self::QUIZ_LOSE_PRIZE - $commission;
            (new Revocoin($this->bot))->transaction(self::QUIZ_LOSE_PRIZE, $this->bot->getBotId(), userId());
            $this->bot->sendMessage("-" . $price . 'R у ' . $this->bot->getUserNick());
        }
    }
}
