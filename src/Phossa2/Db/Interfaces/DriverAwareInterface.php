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

namespace Phossa2\Db\Interfaces;

/**
 * DriverAwareInterface
 *
 * @package Phossa2\Db
 * @author  Hong Zhang <phossa@126.com>
 * @version 2.0.0
 * @since   2.0.0 added
 */
interface DriverAwareInterface
{
    /**
     * Set the driver
     *
     * @param  DriverInterface $driver
     * @return $this
     * @access public
     * @api
     */
    public function setDriver(DriverInterface $driver);

    /**
     * Get the driver
     *
     * @return value
     * @throws LogicException if driver not set
     * @access public
     * @api
     */
    public function getDriver()/*# : DriverInterface */;
}
