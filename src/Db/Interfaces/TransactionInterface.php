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
 * TransactionInterface
 *
 * Part of DriverInterface, transaction related
 *
 * @package Phossa2\Db
 * @author  Hong Zhang <phossa@126.com>
 * @version 2.0.0
 * @since   2.0.0 added
 */
interface TransactionInterface
{
    /**
     * Is in transaction
     *
     * @return bool
     * @access public
     * @api
     */
    public function inTransaction()/*# : bool */;

    /**
     * Begin transaction
     *
     * @return $this
     * @throws LogicException if connect failed
     * @access public
     * @api
     */
    public function begin();

    /**
     * Commit transaction
     *
     * @return $this
     * @access public
     * @api
     */
    public function commit();

    /**
     * Rollback transaction
     *
     * @return $this
     * @throws RuntimeException if called before transaction begins
     * @access public
     * @api
     */
    public function rollback();
}
