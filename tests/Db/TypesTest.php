<?php

namespace Phossa2\Db;

/**
 * Types test case.
 */
class TypesTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
    }

    /**
     * Cleans up the environment after running a test.
     */
    protected function tearDown()
    {
        parent::tearDown();
    }

    /**
     * Tests Types::guessType()
     *
     * @covers Phossa2\Db\Types::guessType
     */
    public function testGuessType()
    {
        $this->assertEquals(\PDO::PARAM_NULL, Types::guessType(null));
        $this->assertEquals(\PDO::PARAM_INT, Types::guessType(2));
        $this->assertEquals(\PDO::PARAM_BOOL, Types::guessType(true));
        $this->assertEquals(\PDO::PARAM_STR, Types::guessType('2'));
    }
}

