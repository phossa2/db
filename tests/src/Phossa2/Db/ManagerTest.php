<?php

namespace Phossa2\Db;

use Phossa2\Db\Driver\Pdo\Driver as PDO_Driver;

/**
 * Manager test case.
 */
class ManagerTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     * @var Manager
     */
    private $object;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->object = new Manager();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        $this->object = null;
        parent::tearDown();
    }

    /**
     * Tests Manager->addDriver()
     *
     * @covers Phossa2\Db\Manager::addDriver
     * @covers Phossa2\Db\Manager::getDriver
     */
    public function testAddDriver1()
    {
        $driver = new PDO_Driver([]);
        $this->object->addDriver($driver);
        $this->assertEquals($driver, $this->object->getDriver());

        $driver->addTag('RW');
        $this->assertEquals($driver, $this->object->getDriver('RW'));
    }

    /**
     * Tests Manager->addDriver()
     *
     * @covers Phossa2\Db\Manager::addDriver
     * @covers Phossa2\Db\Manager::getDriver
     * @expectedException Phossa2\Db\Exception\NotFoundException
     * @expectedExceptionCode Phossa2\Db\Message\Message::DB_DRIVER_NOTFOUND
     */
    public function testAddDriver2()
    {
        $driver = new PDO_Driver([]);
        $driver->addTag('RW');
        $this->assertEquals($driver, $this->object->getDriver('RO'));
    }
}
