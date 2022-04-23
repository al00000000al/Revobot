<?php

namespace Revobot\Test\Revobot\Commands;

use Revobot\Commands\RandCmd;
use PHPUnit\Framework\TestCase;

class RandCmdTest extends TestCase
{

    public function testExec()
    {
        $class1 = new RandCmd(null);
        $class2 = new RandCmd('40 41');
        $class3 = new RandCmd('42 41');
        self::assertStringStartsWith('Ваше число: ', $class1->exec());
        self::assertStringStartsWith('Ваше число: 4', $class2->exec());
        self::assertEquals('Введите /rand min max', $class3->exec());
    }
}
