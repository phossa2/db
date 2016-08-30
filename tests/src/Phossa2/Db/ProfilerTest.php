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
    private $profiler;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();

        // TODO Auto-generated ProfilerTest::setUp()

        $this->profiler = new Profiler(/* parameters */);
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        // TODO Auto-generated ProfilerTest::tearDown()
        $this->profiler = null;

        parent::tearDown();
    }

    /**
     * Tests Profiler->setSql()
     */
    public function testSetSql()
    {
    }

    /**
     * Tests Profiler->setParameters()
     */
    public function testSetParameters()
    {
    }

    /**
     * Tests Profiler->getSql()
     */
    public function testGetSql()
    {
    }

    /**
     * Tests Profiler->setExecutionTime()
     */
    public function testSetExecutionTime()
    {
    }

    /**
     * Tests Profiler->getExecutionTime()
     */
    public function testGetExecutionTime()
    {
    }
}
