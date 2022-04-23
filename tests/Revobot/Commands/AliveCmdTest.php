<?php

namespace Revobot\Test\Revobot\Commands;

use Revobot\Commands\AliveCmd;
use PHPUnit\Framework\TestCase;

class AliveCmdTest extends TestCase
{

    public function testExec()
    {
        $actualClass = new AliveCmd(null);
        $this->assertEquals('Жив!', $actualClass->exec());
    }
}
