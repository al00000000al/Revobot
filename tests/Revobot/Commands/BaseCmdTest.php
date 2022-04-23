<?php

namespace Revobot\Test\Revobot\Commands;

use Revobot\Commands\BaseCmd;
use PHPUnit\Framework\TestCase;

class BaseCmdTest extends TestCase
{

    public function testGetDescription()
    {
        $baseCmd = new BaseCmd(' test ');
        self::assertEquals('Base cmd', $baseCmd->getDescription());
    }

    public function testGetInput(){
        $baseCmd = new BaseCmd(' test ');
        self::assertEquals('test', $baseCmd->getInput());
    }

    public function testExec()
    {
        $baseCmd = new BaseCmd(' test ');
        self::assertEquals('test', $baseCmd->exec());
    }

}
