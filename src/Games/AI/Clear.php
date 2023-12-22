<?php

namespace Revobot\Games\AI;


class Clear
{
    public static function all(GptPMC $GptPMC)
    {
        $GptPMC->deleteHistory();
        $GptPMC->setContext("");
    }
}
