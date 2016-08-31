<?php

namespace Phossa2\Db\Driver\Pdo;

/**
 * Result test case.
 */
class ResultTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Driver
     */
    protected $driver;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->driver = new Driver([
            'dsn' => 'mysql:dbname=mysql;host=127.0.0.1'
        ]);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->driver = null;
        parent::tearDown();
    }

    /**
     * Tests Result->isSelect()
     *
     * @covers Phossa2\Db\Driver\Pdo\Result::isSelect()
     */
    public function testIsSelect()
    {
        if ($this->driver->query("SELECT 1")) {
            $this->assertTrue($this->driver->isSelect());
        } else {
            $this->assertTrue(false);
        }

        if ($this->driver->query("DROP TABLE IF EXISTS test")) {
            $this->assertFalse($this->driver->isSelect());
        } else {
            $this->assertTrue(false);
        }
    }

    /**
     * Tests Result->fieldCount()
     *
     * @covers Phossa2\Db\Driver\Pdo\Result::fieldCount
     */
    public function testFieldCount()
    {
        // SELECT has fieldCount
        if ($this->driver->query("SELECT 1, 2")) {
            $this->assertTrue($this->driver->fieldCount() === 2);
        } else {
            $this->assertTrue(false);
        }

        // DDL has zero fieldCount
        if ($this->driver->query("DROP TABLE IF EXISTS test")) {
            $this->assertTrue($this->driver->fieldCount() === 0);
        } else {
            $this->assertTrue(false);
        }
    }

    /**
     * Tests Result->rowCount()
     *
     * @covers Phossa2\Db\Driver\Pdo\Result::rowCount
     */
    public function testRowCount()
    {
        // SELECT has rowCount
        if ($this->driver->query("SELECT 1, 2")) {
            $this->assertTrue($this->driver->rowCount() === 1);
        } else {
            $this->assertTrue(false);
        }

        // DDL has zero rowCount
        if ($this->driver->query("DROP TABLE IF EXISTS test")) {
            $this->assertTrue($this->driver->rowCount() === 0);
        } else {
            $this->assertTrue(false);
        }
    }

    /**
     * Tests Result->affectedRows()
     *
     * @covers Phossa2\Db\Driver\Pdo\Result::affectedRows
     */
    public function testAffectedRows()
    {
        if ($this->driver->query("DROP TABLE IF EXISTS test")) {
            $this->assertTrue($this->driver->affectedRows() === 0);
        } else {
            $this->assertTrue(false);
        }
    }

    /**
     * Tests Result->fetchAll()
     *
     * @covers Phossa2\Db\Driver\Pdo\Result::fetchAll
     */
    public function testFetchAll()
    {
        $this->driver->query("SELECT 1, 2");
        $this->assertEquals([[1=>1, 2=>2]], $this->driver->fetchAll());

        $this->driver->query("SELECT 'test' AS t");
        $this->assertEquals([['t' => 'test']], $this->driver->fetchAll());
    }

    /**
     * Tests Result->fetchRow()
     *
     * @covers Phossa2\Db\Driver\Pdo\Result::fetchRow
     */
    public function testFetchRow()
    {
        $this->driver->query("SELECT 1, 2");
        $this->assertEquals([[1=>1, 2=>2]], $this->driver->fetchRow());

        $this->driver->query("SELECT 'test' AS t");
        $this->assertEquals([['t' => 'test']], $this->driver->fetchRow());
    }

    /**
     * Tests Result->fetchCol()
     *
     * @covers Phossa2\Db\Driver\Pdo\Result::fetchCol
     */
    public function testFetchCol()
    {
        $this->driver->query("SELECT 1, 2");
        $this->assertEquals([1], $this->driver->fetchCol(1));

        $this->driver->query("SELECT 'test' AS t");
        $this->assertEquals(['test'], $this->driver->fetchCol('t'));
    }
}
