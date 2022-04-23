<?php

namespace Revobot\Test\Revobot\Commands;

use Revobot\Commands\YnCmd;
use PHPUnit\Framework\TestCase;

class YnCmdTest extends TestCase
{

    public function testExec()
    {
        $class1 = new YnCmd(null);
        $class2 = new YnCmd('Я попугай?');
        self::assertEquals('Введите /yn <событие>', $class1->exec());
        self::assertEquals('Может быть', $class2->exec());
    }
}
