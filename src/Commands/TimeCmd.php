<?php

namespace Revobot\Commands;

use Revobot\Revobot;
use Revobot\Util\PMC;

class TimeCmd extends BaseCmd
{
    const KEYS = ['time', 'тайм', 'время'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'Правильное время';
    private const PMC_USER_TIMEZONE_KEY = "user_timezone_"; //.$provider.$user_id
    private const DATE_FORMAT = 'Y-m-d H:i:s';
    public const MSK_TZ = 3;

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
            PMC::set($this->getKey(), $tz);
        } else {
            $result = PMC::get($this->getKey());
        }

        if (isset($result)) {
            $tz = (string)$result;
        }
        if (!$tz) {
            return date(self::DATE_FORMAT);
        }

        return date(self::DATE_FORMAT, time() + ((int)$tz - self::MSK_TZ) * 60 * 60);
    }

    /**
     * @return string
     */
    private function getKey(): string
    {
        return self::PMC_USER_TIMEZONE_KEY . $this->bot->provider . userId();
    }
}
