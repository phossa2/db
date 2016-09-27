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

use Phossa2\Db\Exception\LogicException;
use Phossa2\Db\Exception\NotFoundException;

/**
 * ManagerInterface
 *
 * Db manager interface
 *
 * @package Phossa2\Db
 * @author  Hong Zhang <phossa@126.com>
 * @version 2.0.0
 * @since   2.0.0 added
 */
interface ManagerInterface
{
    /**
     * Add a named driver to the pool
     *
     * @param  DriverInterface $driver
     * @return $this
     * @throws LogicException if $name set already
     * @access public
     * @api
     */
    public function addDriver(DriverInterface $driver);

    /**
     * Get a matched driver
     *
     * @return DriverInterface
     * @throws NotFoundException if no matching driver found
     * @access public
     * @api
     */
    public function getDriver()/*# : DriverInterface */;
}
