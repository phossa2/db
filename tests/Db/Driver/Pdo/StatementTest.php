<?php

namespace Phossa2\Db\Driver\Pdo;

/**
 * Statement test case.
 */
class StatementTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     * @var Driver
     */
    protected $driver;

    /**
     *
     * @var Statement
     */
    protected $statement;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->driver = new Driver([
            'dsn' => 'mysql:dbname=mysql;host=127.0.0.1;charset=utf8'
        ]);
        $this->statement = (new Statement())->setDriver($this->driver);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->driver = null;
        $this->statement = null;
        parent::tearDown();
    }

    /**
     * @covers Phossa\Db\Driver\Pdo\Statement::prepare()
     */
    public function testPrepare1()
    {
        $this->assertTrue($this->statement->prepare('SELECT ?, ?'));
    }

    /**
     * Test no sql prepared yet
     *
     * @covers Phossa2\Db\Driver\Pdo\Statement::execute()
     * @expectedException Phossa2\Db\Exception\RuntimeException
     * @expectedExceptionCode Phossa2\Db\Message\Message::DB_STMT_NOTPREPARED
     */
    public function testExecute1()
    {
        $this->statement->execute([1]);
    }

    /**
     * @covers Phossa2\Db\Driver\Pdo\Statement::prepare()
     * @covers Phossa2\Db\Driver\Pdo\Statement::execute()
     */
    public function testExecute2()
    {
        // PDO has named parameters but Mysqli NOT
        if ($this->statement instanceof Statement) {
            $this->driver->setAttribute('PDO::ATTR_EMULATE_PREPARES', true);
            $this->statement->prepare("SELECT :idx, :color");
            $this->statement->execute(['idx' => 1, 'color' => 'red']);
            $this->assertEquals(
                [[1 => "1", "red" => "red"]],
                $this->statement->getResult()->fetchRow()
            );
        } else {
            $this->statement->prepare("SELECT 1, ?");
            $this->statement->execute(['red']);
            $this->assertEquals(
                [[1 => "1", "?" => "red"]],
                $this->statement->getResult()->fetchRow()
            );
        }
    }
}

