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

/**
 * ConnectInterface
 *
 * Part of DriverInterface, connection related
 *
 * @package Phossa2\Db
 * @author  Hong Zhang <phossa@126.com>
 * @version 2.0.0
 * @since   2.0.0 added
 */
interface ConnectInterface
{
    /**
     * Connect to the db
     *
     * @return $this
     * @throws LogicException if connect failed
     * @access public
     * @api
     */
    public function connect();

    /**
     * Disconnect with the db
     *
     * @return $this
     * @access public
     * @api
     */
    public function disconnect();

    /**
     * Is connection established
     *
     * @return bool
     * @access public
     * @api
     */
    public function isConnected()/*# : bool */;

    /**
     * Is connection alive
     *
     * @param  bool $connect force connect first
     * @return bool
     * @access public
     * @api
     */
    public function ping(/*# bool */ $connect = false)/*# : bool */;

    /**
     * Get the connection link
     *
     * @return mixed
     * @throws LogicException if connect failed
     * @access public
     * @api
     */
    public function getLink();

    /**
     * Set connection specific attribute
     *
     * @param  string attribute
     * @param  mixed $value
     * @return $this
     * @throws LogicException if attribute unknown
     * @access public
     * @api
     */
    public function setAttribute(/*# string */ $attribute, $value);

    /**
     * Get connection specific attribute
     *
     * @param  string attribute
     * @return mixed
     * @throws LogicException if attribute unknown
     * @access public
     * @api
     */
    public function getAttribute(/*# string */ $attribute);
}
