<?php

namespace Revobot\Test\Revobot\Commands;

use Revobot\Commands\CalcCmd;
use PHPUnit\Framework\TestCase;

class CalcCmdTest extends TestCase
{

    public function testExec()
    {
        $calc1 = new CalcCmd(null);
        $calc2 = new CalcCmd(' ');
        $calc3 = new CalcCmd('1+ 1*5');
        self::assertEquals('Введите /calc [выражение]',  $calc1->exec());
        self::assertEquals('Введите /calc [выражение]',  $calc2->exec());
        self::assertEquals('6',  $calc3->exec());
    }
}
