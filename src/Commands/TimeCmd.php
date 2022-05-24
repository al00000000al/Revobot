<?php

namespace Revobot\Commands;

use Revobot\Revobot;

class TimeCmd extends BaseCmd
{
    private const PMC_USER_TIMEZONE_KEY = "user_timezone_";//.$provider.$user_id
    private const DATE_FORMAT = 'Y-m-d H:i:s';

    private Revobot $bot;

    public function __construct($input, Revobot $bot)
    {
        parent::__construct($input);
        $this->bot = $bot;
        $this->setDescription("/time\n/time +2");
    }

    public function exec(): string
    {
        $tz = null;
        if (!empty($this->input)) {
            $tz = (string)$this->input;
            $this->bot->pmc->set($this->getKey(), $tz);
        } else {
            $result = $this->bot->pmc->get($this->getKey());
        }

        if (isset($result)) {
            $tz = (string)$result;
        }
        if (!$tz) {
            return date(self::DATE_FORMAT);
        }

        return date(self::DATE_FORMAT, time() + (int)$tz * 60 * 60);

    }

    /**
     * @return string
     */
    private function getKey(): string
    {
        return self::PMC_USER_TIMEZONE_KEY . $this->bot->provider . $this->bot->getUserId();
    }
}
