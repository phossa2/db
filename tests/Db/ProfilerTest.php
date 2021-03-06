<?php

namespace Phossa2\Db;

/**
 * Profiler test case.
 */
class ProfilerTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     * @var Profiler
     */
    private $object;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->object = new Profiler();
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
     * getPrivateProperty
     *
     * @param  string $propertyName
     * @return the property
     */
    public function getPrivateProperty($propertyName) {
        $reflector = new \ReflectionClass($this->object);
        $property  = $reflector->getProperty($propertyName);
        $property->setAccessible(true);

        return $property->getValue($this->object);
    }

    /**
     * Call protected/private method of a class.
     *
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    protected function invokeMethod($methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass($this->object);
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($this->object, $parameters);
    }

    /**
     * Tests Profiler->setSql()
     *
     * @covers Phossa2\Db\Profiler::setSql
     */
    public function testSetSql()
    {
        $sql = 'SELECT 1';
        $this->object->setSql($sql);
        $this->assertEquals($sql, $this->getPrivateProperty('sql'));
    }

    /**
     * Tests Profiler->setParameters()
     *
     * @covers Phossa2\Db\Profiler::setParameters
     */
    public function testSetParameters()
    {
        $params = [1,2];
        $this->object->setParameters($params);
        $this->assertEquals($params, $this->getPrivateProperty('params'));
    }

    /**
     * Tests Profiler->getSql()
     *
     * @covers Phossa2\Db\Profiler::getSql
     */
    public function testGetSql()
    {
        $this->assertEquals('', $this->object->getSql());

        $sql = 'SELECT 1';
        $this->object->setSql($sql);
        $this->assertEquals($sql, $this->object->getSql());
    }

    /**
     * Tests Profiler->startWatch()
     *
     * @covers Phossa2\Db\Profiler::startWatch()
     * @covers Phossa2\Db\Profiler::stopWatch()
     * @covers Phossa2\Db\Profiler::getExecutionTime()
     */
    public function testStartWatch()
    {
        $this->object->startWatch();
        usleep(200);
        $this->object->stopWatch();
        $this->assertTrue($this->object->getExecutionTime() < 1.0);
    }
}
