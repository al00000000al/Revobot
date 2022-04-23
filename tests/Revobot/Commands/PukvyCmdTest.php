<?php

namespace Revobot\Test\Revobot\Commands;

use Revobot\Commands\PukvyCmd;
use PHPUnit\Framework\TestCase;

class PukvyCmdTest extends TestCase
{

    public function testExec()
    {
        $class1 = new PukvyCmd('');
        $class2 = new PukvyCmd('одно');
        $class3 = new PukvyCmd('два слова');
        $class4 = new PukvyCmd('ASDдва ersслова');
        self::assertEquals('Введите /pukvy два слова', $class1->exec());
        self::assertEquals('Введите /pukvy два слова', $class2->exec());
        self::assertEquals('сло двава', $class3->exec());
        self::assertEquals('сло двава', $class4->exec());
    }
}
