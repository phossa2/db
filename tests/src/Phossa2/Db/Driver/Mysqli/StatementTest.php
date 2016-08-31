<?php

namespace Phossa2\Db\Driver\Mysqli;

require_once __DIR__ . '/../Pdo/StatementTest.php';

use Phossa2\Db\Driver\Pdo\StatementTest;

/**
 * Statement test case.
 */
class MysqliStatementTest extends StatementTest
{
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();

        $this->driver = new Driver([
            'db'        => 'mysql',
            'host'      => '127.0.0.1',
            'charset'   => 'utf8'
        ]);
        $this->statement = (new Statement())->setDriver($this->driver);
    }
}
