<?php

namespace Revobot\Commands;

use Revobot\Numerology\PentabaseLogic;

class PentabaseCmd extends BaseCmd
{
    const KEYS = ['pentabase', 'пентабазис'];
    const IS_ENABLED = true;
    const HELP_DESCRIPTION = 'Получить персональный психологический код личности.';

    public function __construct(string $input)
    {
        parent::__construct($input);
        $this->setDescription('/pentabase Получить персональный психологический код личности');
    }

    public function exec(): string
    {
        if (empty($this->input)) {
            return $this->description;
        }
        $logic = new PentabaseLogic();
        $res = $logic->analyze($this->input);
        $msg = "📊 *Код Пентабазис:* `" . $res['code'] . "`\n";
        $msg .= "🏛 *Ценностная опора:* " . $res['base']['value'] . "\n";
        $msg .= "⭐ *Доминанта:* " . $res['main']['name'] . " (" . $res['main']['role'] . ")\n\n";
        $msg .= "💡 *Рекомендация:* " . $res['advice'];
        return $msg;
    }
}
