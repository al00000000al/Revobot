<?php

namespace Revobot\Test\Revobot\Commands;

use Revobot\Commands\WhenCmd;
use PHPUnit\Framework\TestCase;

class WhenCmdTest extends TestCase
{

    public function testExec()
    {
        $class1 = new WhenCmd(null);
        $class2 = new WhenCmd('когда суп');
        self::assertEquals('Введите /when событие', $class1->exec());
        self::assertStringStartsWith('Я думаю это произойдет ', $class2->exec());
    }
}
