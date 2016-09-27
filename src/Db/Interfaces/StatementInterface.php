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

use Phossa2\Db\Exception\RuntimeException;
use Phossa2\Db\Exception\NotFoundException;

/**
 * StatementInterface
 *
 * @package Phossa2\Db
 * @author  Hong Zhang <phossa@126.com>
 * @see     DriverAwareInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
interface StatementInterface extends DriverAwareInterface
{
    /**
     * Prepare the SQL statement
     *
     * @param  string $sql
     * @return bool
     * @throws RuntimeException
     * @access public
     * @api
     */
    public function prepare(/*# string */ $sql)/*# : bool */;

    /**
     * Execute the prepared statement
     *
     * @param  array $parameters
     * @return bool
     * @throws RuntimeException
     * @access public
     * @api
     */
    public function execute(array $parameters = [])/*# : bool */;

    /**
     * Returns the result set
     *
     * @return ResultInterface
     * @throws NotFoundException if result not found
     * @access public
     * @api
     */
    public function getResult()/*# : ResultInterface */;

    /**
     * Close this statement
     *
     * @access public
     */
    public function close();
}
