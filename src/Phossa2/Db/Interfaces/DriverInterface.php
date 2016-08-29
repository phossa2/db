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

use Phossa2\Shared\Error\ErrorAwareInterface;

/**
 * DriverInterface
 *
 * Db driver interface
 *
 * @package Phossa2\Db
 * @author  Hong Zhang <phossa@126.com>
 * @see     ConnectInterface
 * @see     TransactionInterface
 * @see     ErrorAwareInterface
 * @see     ProfilerAwareInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
interface DriverInterface extends ConnectInterface, TransactionInterface, ErrorAwareInterface, ProfilerAwareInterface
{
    /**
     * Prepare the sql into statement object
     *
     * @param  string $sql SQL statement
     * @return bool
     * @access public
     * @api
     */
    public function prepare(/*# string */ $sql)/*# : bool */;

    /**
     * Get the prepared statement after $this->prepare()
     *
     * @return StatementInterface
     * @access public
     * @api
     */
    public function getStatement()/*# : StatementInterface */;

    /**
     * Execute the sql with given parameters
     *
     * @param  string $sql SQL statement
     * @param  array $parameters
     * @return bool
     * @access public
     * @api
     */
    public function query(
        /*# string */ $sql,
        array $parameters = []
    )/*# : bool */;

    /**
     * Get the result after $this->query()
     *
     * @return ResultInterface
     * @access public
     * @api
     */
    public function getResult()/*# : ResultInterface */;

    /**
     * Affected rows after $this->query()
     *
     * @return int
     * @access public
     * @api
     */
    public function affectedRows()/*# : int */;

    /**
     * Get last insert id
     *
     * @param  string $name sequence name if any
     * @return string|null
     * @access public
     * @api
     */
    public function lastInsertId($name = null);
}
