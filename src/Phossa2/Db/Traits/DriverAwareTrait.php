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

namespace Phossa2\Db\Traits;

use Phossa2\Db\Message\Message;
use Phossa2\Db\Exception\LogicException;
use Phossa2\Db\Interfaces\DriverInterface;
use Phossa2\Db\Interfaces\DriverAwareInterface;

/**
 * DriverAwareTrait
 *
 * @package Phossa2\Db
 * @author  Hong Zhang <phossa@126.com>
 * @see     DriverAwareInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
trait DriverAwareTrait
{
    /**
     * the driver
     *
     * @var    DriverInterface
     * @access protected
     */
    protected $driver;

    /**
     * {@inheritDoc}
     */
    public function setDriver(DriverInterface $driver)
    {
        $this->driver = $driver;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getDriver()/*# : DriverInterface */
    {
        if (null === $this->driver) {
            throw new LogicException(
                Message::get(Message::DB_DRIVER_NOTSET),
                Message::DB_DRIVER_NOTSET
            );
        }
        return $this->driver;
    }
}
