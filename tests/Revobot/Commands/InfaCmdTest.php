<?php

namespace Revobot\Test\Revobot\Commands;

use Revobot\Commands\InfaCmd;
use PHPUnit\Framework\TestCase;

class InfaCmdTest extends TestCase
{

    public function testExec()
    {
        $class = new InfaCmd('тест');
        self::assertStringStartsWith('Вероятность события: ', $class->exec());
    }
}
