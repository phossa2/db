<?php

namespace Phossa2\Db\Driver\Mysqli;

require_once __DIR__ . '/../Pdo/ResultTest.php';

use Phossa2\Db\Driver\Pdo\ResultTest;

/**
 * Result test case.
 */
class MysqliResultTest extends ResultTest
{
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        $this->driver = new Driver([
            'db'        => 'mysql',
            'host'      => '127.0.0.1',
            'charset'   => 'utf8'
        ]);
    }
}
