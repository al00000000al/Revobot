<?php

namespace Revobot\Test\Revobot\Commands;

use Revobot\Commands\BashCmd;
use PHPUnit\Framework\TestCase;

class BashCmdTest extends TestCase
{

    public function testExec()
    {
        $bashCmd = new BashCmd(null);
        self::assertNotEquals('Сервер не доступен',  $bashCmd->exec());
    }
}
