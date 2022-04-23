<?php

namespace Revobot\Test\Revobot;

use Revobot\Revobot;
use PHPUnit\Framework\TestCase;

class RevobotTest extends TestCase
{

    public function testPrintHelloWorld()
    {
        $actualClass = new Revobot();
        $this->assertEquals('Hello World', $actualClass->printHelloWorld());
    }
}
