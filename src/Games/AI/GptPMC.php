<?php

namespace Revobot\Games\AI;

use Revobot\Util\PMC;

class GptPMC
{
    private const PMC_USER_AI_KEY = 'pmc_user_ai_';
    private const PMC_USER_AI_PERM_KEY = 'pmc_user_perm_ai_';
    private const PMC_USER_AI_HISTORY_KEY = 'pmc_user_ai_history_';
    private const PMC_USER_AI_INPUT_KEY = 'pmc_user_ai_input_';

    private int $user_id;
    private string $provider;

    public function __construct(int $user_id, string $provider = 'tg')
    {
        $this->user_id = $user_id;
        $this->provider = $provider;
    }

    public function getContext()
    {
        return (string) PMC::get(self::getContextKey($this->user_id, $this->provider));
    }

    public function getContextPermanent()
    {
        return (string) PMC::get(self::getContextPermanentKey($this->user_id, $this->provider));
    }

    public function setContext(string $context)
    {
        PMC::set(self::getContextKey($this->user_id, $this->provider), $context);
    }

    public function setContextPermanent(string $context)
    {
        PMC::set(self::getContextPermanentKey($this->user_id, $this->provider), $context);
    }

    public function getHistory()
    {
        return (array) json_decode(PMC::get(self::getHistoryKey($this->user_id, $this->provider)), true);
    }

    public function setHistory(array $history)
    {
        PMC::set(self::getHistoryKey($this->user_id, $this->provider),  json_encode($history));
    }

    public function setInput(string $message)
    {
        PMC::set(self::getInputKey($this->user_id, $this->provider), ($message));
    }

    public function deleteHistory()
    {
        PMC::delete(self::getHistoryKey($this->user_id, $this->provider));
    }

    private static function getContextKey(int $user_id, string $provider)
    {
        return self::PMC_USER_AI_KEY . $provider . $user_id;
    }

    private static function getContextPermanentKey(int $user_id, string $provider)
    {
        return self::PMC_USER_AI_PERM_KEY . $provider . $user_id;
    }

    private static function getHistoryKey(int $user_id, string $provider)
    {
        return self::PMC_USER_AI_HISTORY_KEY . $provider . $user_id;
    }

    public static function getInputKey(int $user_id, string $provider)
    {
        return self::PMC_USER_AI_INPUT_KEY . $provider . $user_id;
    }
}
