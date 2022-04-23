<?php

namespace Revobot\Test\Revobot\Commands;

use Revobot\Commands\HelpCmd;
use PHPUnit\Framework\TestCase;

class HelpCmdTest extends TestCase
{

    public function testExec()
    {
        $class = new HelpCmd(null);
        self::assertNotEmpty($class->exec());
    }
}
