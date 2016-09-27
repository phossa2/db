<?php

namespace Phossa2\Db\Driver\Mysqli;

require_once __DIR__ . '/../Pdo/DriverTest.php';

use Phossa2\Db\Driver\Pdo\DriverTest;

/**
 * Driver test case.
 */
class MysqliDriverTest extends DriverTest
{
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        $this->object = new Driver([
            'db'        => 'mysql',
            'host'      => '127.0.0.1',
            'charset'   => 'utf8'
        ]);
    }

    public function testRealSetAttribute2()
    {
    }

    public function testSetAttribute()
    {
    }
}
