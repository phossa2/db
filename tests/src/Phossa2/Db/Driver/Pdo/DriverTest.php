<?php

namespace Phossa2\Db\Driver\Pdo;

use Phossa2\Db\Types;
use Phossa2\Db\Interfaces\StatementInterface;

/**
 * Driver test case.
 */
class DriverTest extends \PHPUnit_Framework_TestCase
{
    /**
     *
     * @var Driver
     */
    protected $object;

    /**
     * Prepares the environment before running a test.
     */
    protected function setUp()
    {
        parent::setUp();
        $this->object = new Driver([
            'dsn' => 'mysql:dbname=mysql;host=127.0.0.1;charset=utf8'
        ]);
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
     * getPrivateProperty
     *
     * @param 	string $propertyName
     * @return	the property
     */
    public function getPrivateProperty($propertyName) {
        $reflector = new \ReflectionClass($this->object);
        $property  = $reflector->getProperty($propertyName);
        $property->setAccessible(true);

        return $property->getValue($this->object);
    }

    /**
     * @covers Phossa2\Db\Driver\Pdo\Driver::extensionLoaded()
     */
    public function testExtensionLoaded()
    {
        $this->assertTrue($this->invokeMethod('extensionLoaded'));
    }

    /**
     * @covers Phossa2\Db\Driver\Pdo\Driver::realQuote()
     */
    public function testRealQuote()
    {
        // connect first
        $this->object->connect();

        // quote null
        $this->assertEquals(
            "''",
            $this->invokeMethod('realQuote', [ null, Types::PARAM_NULL] )
        );

        // quote int
        $this->assertEquals(
            "'12'",
            $this->invokeMethod('realQuote', [ 12, Types::PARAM_INT] )
        );

        // quote bool
        $this->assertEquals(
            "'1'",
            $this->invokeMethod('realQuote', [ true, Types::PARAM_BOOL] )
        );
        $this->assertEquals(
            "''",
            $this->invokeMethod('realQuote', [ false, Types::PARAM_BOOL] )
        );

        // quote wild string
        $this->assertEquals(
            "'test\'s'",
            $this->invokeMethod('realQuote', [ "test's", Types::PARAM_STR] )
        );
    }

    /**
     * @covers Phossa2\Db\Driver\Pdo\Driver::realPing()
     */
    public function testRealPing()
    {
        $this->object->connect();
        $this->assertTrue($this->invokeMethod('realPing'));
    }

    /**
     *
     * @covers Phossa2\Db\Pdo\Driver::realSetAttribute
     * @expectedException Phossa2\Db\Exception\LogicException
     * @expectedExceptionMessageRegExp /unknown/
     */
    public function testRealSetAttribute1()
    {
        $this->invokeMethod(
            'realSetAttribute',
            [ 'test', 'bingo']
        );
    }

    /**
     *
     * @covers Phossa2\Db\Pdo\Driver::realGetAttribute()
     * @expectedException Phossa2\Db\Exception\LogicException
     * @expectedExceptionMessageRegExp /unknown/
     */
    public function testRealGetAttribute1()
    {
        $this->invokeMethod(
            'realGetAttribute',
            [ 'test']
        );
    }

    /**
     *
     * @covers Phossa2\Db\Driver\Pdo\Driver::realSetAttribute()
     * @covers Phossa2\Db\Driver\Pdo\Driver::realGetAttribute()
     */
    public function testRealSetAttribute2()
    {
        $this->object->connect();

        $this->assertFalse(
            \PDO::ERRMODE_WARNING ===
            $this->invokeMethod(
                'realGetAttribute',
                [ 'PDO::ATTR_ERRMODE' ]
            )
        );

        $this->invokeMethod(
            'realSetAttribute',
            [ 'PDO::ATTR_ERRMODE', \PDO::ERRMODE_WARNING ]
        );

        $this->assertEquals(
            \PDO::ERRMODE_WARNING,
            $this->invokeMethod(
                'realGetAttribute',
                [ 'PDO::ATTR_ERRMODE' ]
            )
        );
    }

    /**
     * @covers Phossa2\Db\Driver\Pdo\Driver::disConnect()
     * @covers Phossa2\Db\Driver\Pdo\Driver::getLink()
     * @covers Phossa2\Db\Driver\Pdo\Driver::isConnected()
     */
    public function testDisConnect()
    {
        $this->assertFalse($this->object->isConnected());
        $this->object->connect();
        //$this->assertTrue($this->object->getLink() instanceof \PDO);
        $this->assertTrue($this->object->isConnected());
        $this->object->disconnect();
        $this->assertFalse($this->object->isConnected());
    }

    /**
     * @covers Phossa2\Db\Driver\Pdo\Driver::ping()
     */
    public function testPing()
    {
        $this->assertFalse($this->object->ping());
        $this->assertTrue($this->object->ping(true));
    }

    /**
     * @covers Phossa2\Db\Driver\Pdo\Driver::setAttribute()
     * @covers Phossa2\Db\Driver\Pdo\Driver::getAttribute()
     */
    public function testSetAttribute()
    {
        $this->assertFalse(
            \PDO::ERRMODE_WARNING ===
            $this->object->getAttribute('PDO::ATTR_ERRMODE')
        );

        $this->object->setAttribute('PDO::ATTR_ERRMODE', \PDO::ERRMODE_WARNING);

        $this->assertTrue(
            \PDO::ERRMODE_WARNING ===
            $this->object->getAttribute('PDO::ATTR_ERRMODE')
        );
    }

    /**
     * @covers Phossa2\Db\Driver\Pdo\Driver::prepare()
     */
    public function testPrepare()
    {
        $this->object->prepare('SELECT ? AS col');
        $stmt = $this->object->getStatement();
        $this->assertTrue($stmt instanceof StatementInterface);

        $stmt->execute([1]);
        $res = $stmt->getResult();
        $this->assertEquals(["1"], $res->fetchCol('col'));

        $stmt->execute([3]);
        $res2 = $stmt->getResult();
        $this->assertEquals(["3"], $res2->fetchCol('col'));
    }

    /**
     * @covers Phossa2\Db\Driver\Pdo\Driver::query()
     */
    public function testQuery1()
    {
        // successful execute
        $this->assertTrue($this->object->query('
            DROP TABLE IF EXISTS `bingo`
        '));

        $sql = <<<EOF
            CREATE TABLE `bingo` (
                `grp_id`   INT         NOT NULL AUTO_INCREMENT,
                `grp_name` VARCHAR(20) NOT NULL DEFAULT '',
                PRIMARY KEY (`grp_id`)
            )
EOF;
        $this->assertTrue($this->object->query($sql));

        $this->assertTrue($this->object->query('
            INSERT INTO `bingo` (`grp_name`) VALUES (?)
        ', ['wow']));
        $this->assertEquals(1, $this->object->affectedRows());
        $this->assertEquals(1, $this->object->lastInsertId());

        $this->object->query("SELECT * FROM bingo");
        $this->assertEquals(
            [["grp_id" => "1", "grp_name" => "wow"]],
            $this->object->fetchRow()
        );

        // failed execute
        $this->assertFalse($this->object->query($sql));
        //$this->assertRegExp("/failed/i", $this->object->getError());
    }

    /**
     * @covers Phossa2\Db\Driver\Pdo\Driver::query()
     */
    public function testQuery2()
    {
        $this->object->query('SELECT ? AS col', [1]);
        $this->assertEquals(["1"], $this->object->fetchCol('col'));

        $this->assertEquals(
            "SELECT '1' AS col",
            $this->object->getProfiler()->getSql()
        );

        if ($this->object instanceof Driver) {
            $res = $this->object->query(
                'SELECT * FROM user WHERE User = :user',
                ['user' => 'root']
            );

            $this->assertEquals(
                "SELECT * FROM user WHERE User = 'root'",
                $this->object->getProfiler()->getSql()
            );
        }
    }
}
