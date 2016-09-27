<?php
/**
 * Phossa Project
 *
 * PHP version 5.4
 *
 * @category  Library
 * @package   Phossa2\Db
 * @copyright Copyright (c) 2016 phossa.com
 * @license   http://mit-license.org/ MIT License
 * @link      http://www.phossa.com/
 */
/*# declare(strict_types=1); */

namespace Phossa2\Db;

use Phossa2\Db\Message\Message;
use Phossa2\Shared\Base\ObjectAbstract;
use Phossa2\Db\Interfaces\DriverInterface;
use Phossa2\Db\Interfaces\ManagerInterface;
use Phossa2\Db\Exception\NotFoundException;
use Phossa2\Shared\Aware\TagAwareInterface;

/**
 * Manager
 *
 * Db driver manager implementation with tag support
 *
 * - Added 'weight factor' for round-robin fashion of load balancing
 *
 * - driver supports tags, may pick driver by tag
 *
 * ```php
 * $dbm = new Manager();
 *
 * // db writer with weighting factor 1
 * $dbm->addDriver($driver1->addTag('RW'), 1);
 *
 * // db reader with weighting factor 5
 * $dbm->addDriver($driver2->addTag('RO'), 5);
 *
 * // get whatever reader or writer
 * $db = $dbm->getDriver();
 *
 * // get driver by tag
 * $dbReader = $dbm->getDriver('RO');
 * ```
 *
 * @package Phossa2\Db
 * @author  Hong Zhang <phossa@126.com>
 * @see     ObjectAbstract
 * @see     ManagerInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
class Manager extends ObjectAbstract implements ManagerInterface
{
    /**
     * drivers
     *
     * @var    DriverInterface[]
     * @access protected
     */
    protected $drivers = [];

    /**
     * driver weight factor
     *
     * @var    array
     * @access protected
     */
    protected $factors = [];

    /**
     * Ping driver before returns it
     *
     * @var    bool
     * @access protected
     */
    protected $ping_driver = false;

    /**
     * Constructor
     *
     * @param  array $properties
     * @access public
     */
    public function __construct(array $properties = [])
    {
        $this->setProperties($properties);
    }

    /**
     * Specify a weighting factor for the driver. normally 1 - 10
     *
     * {@inheritDoc}
     * @param  int $factor weight factor for round-robin
     */
    public function addDriver(DriverInterface $driver, /*# int */ $factor = 1)
    {
        // get unique driver id
        $id = $this->getDriverId($driver);

        // fix factor range: 1 - 10
        $this->factors[$id] = $this->fixFactor($factor);

        // add to the pool
        $this->drivers[$id] = $driver;

        return $this;
    }

    /**
     * Get a driver with a tag matched
     *
     * {@inheritDoc}
     */
    public function getDriver(/*# string */ $tag = '')/*# : DriverInterface */
    {
        // match drivers
        $matched = $this->driverMatcher($tag);

        if (count($matched) > 0) {
            return $this->drivers[$matched[rand(1, count($matched)) - 1]];
        } else {
            throw new NotFoundException(
                Message::get(Message::DB_DRIVER_NOTFOUND),
                Message::DB_DRIVER_NOTFOUND
            );
        }
    }

    /**
     * Return a unique string id of the driver
     *
     * @param  DriverInterface $driver
     * @return string
     * @access protected
     */
    protected function getDriverId(DriverInterface $driver)/*# : string */
    {
        return spl_object_hash($driver);
    }

    /**
     * Make sure factor in the range of 1 - 10
     *
     * @param  int $factor
     * @return int
     * @access protected
     */
    protected function fixFactor(/*# int */ $factor)/*# : int */
    {
        $f = (int) $factor;
        return $f > 10 ? 10 : ($f < 1 ? 1 : $f);
    }

    /**
     * Match drivers with tag
     *
     * @param  string $tag tag to match
     * @return array
     * @access protected
     */
    protected function driverMatcher(/*# string */ $tag)/*# : array */
    {
        $matched = [];
        foreach ($this->drivers as $id => $driver) {
            if ($this->tagMatched($tag, $driver) && $this->pingDriver($driver)) {
                $this->expandWithFactor($matched, $id);
            }
        }
        return $matched;
    }

    /**
     * Expand into $matched with factor weight
     *
     * @param  array &$matched
     * @param  string $id
     * @access protected
     */
    protected function expandWithFactor(array &$matched, /*# string */ $id)
    {
        // repeat $f times in $matched
        $f = $this->factors[$id];
        for ($i = 0; $i < $f; ++$i) {
            $matched[] = $id;
        }
    }

    /**
     * Match driver with $tag
     *
     * @param  string $tag
     * @param  DriverInterface $driver
     * @return bool
     * @access protected
     */
    protected function tagMatched(
        /*# string */ $tag,
        DriverInterface $driver
    )/*# bool */ {
        // '' matches all
        if ('' === $tag) {
            return true;
        }

        // tag matched
        if ($driver instanceof TagAwareInterface && $driver->hasTag($tag)) {
            return true;
        }

        return false;
    }

    /**
     * Ping driver before match it
     *
     * @param  DriverInterface $driver
     * @access protected
     */
    protected function pingDriver(DriverInterface $driver)/*# : bool */
    {
        return $this->ping_driver ? $driver->ping(true) : true;
    }
}
