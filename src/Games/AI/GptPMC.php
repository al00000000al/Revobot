<?php

namespace Revobot\Games\AI;

class GptPMC
{
    private const PMC_USER_AI_KEY = 'pmc_user_ai_';
    private const PMC_USER_AI_HISTORY_KEY = 'pmc_user_ai_history_';

    private int $user_id;
    private string $provider;

    public function __construct(int $user_id, string $provider = 'tg') {
        global $pmc;

        $this->user_id = $user_id;
        $this->provider = $provider;
    }

    public function getContext() {
        global $pmc;
        return (string) $pmc->get(self::getContextKey($this->user_id, $this->provider));
    }

    public function setContext(string $context) {
        global $pmc;
        $pmc->set(self::getContextKey($this->user_id, $this->provider), $context);
    }

    public function getHistory() {
        global $pmc;
        return (array) json_decode($pmc->get(self::getHistoryKey($this->user_id, $this->provider)), true);
    }

    public function setHistory(array $history) {
        global $pmc;
        $pmc->set(self::getHistoryKey($this->user_id, $this->provider),  json_encode($history));
    }

    public function deleteHistory() {
        global $pmc;
        $pmc->delete(self::getHistoryKey($this->user_id, $this->provider));
    }

    private static function getContextKey(int $user_id, string $provider) {
        return self::PMC_USER_AI_KEY . $provider . $user_id;
    }

    private static function getHistoryKey(int $user_id, string $provider) {
        return self::PMC_USER_AI_HISTORY_KEY . $provider . $user_id;
    }

}
