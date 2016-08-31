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
 * ResultInterface
 *
 * @package Phossa2\Db
 * @author  Hong Zhang <phossa@126.com>
 * @version 2.0.0
 * @since   2.0.0 added
 */
interface ResultInterface
{
    /**
     * Is this a SELECT result
     *
     * @return bool
     * @access public
     */
    public function isSelect()/*# : bool */;

    /**
     * Get field count of the result
     *
     * @return int
     * @access public
     */
    public function fieldCount()/*# : int */;

    /**
     * Get row count of the query result
     *
     * @return int
     * @access public
     */
    public function rowCount()/*# : int */;

    /**
     * Get affected row count for DDL statement
     *
     * @return int
     * @access public
     */
    public function affectedRows()/*# : int */;

    /**
     * Fetch all the result rows
     *
     * @return array
     * @throws RuntimeException if not a SELECT or fetched already
     * @access public
     */
    public function fetchAll()/*# : array */;

    /**
     * Fetch first n'th rows
     *
     * @param  int $rowCount
     * @return array
     * @throws RuntimeException if not a SELECT
     * @access public
     */
    public function fetchRow(/*# int */ $rowCount = 1)/*# : array */;

    /**
     * Fetch the named/positioned field of the first # of rows
     *
     * if $rowCount == 0, fetch col of all the result rows
     *
     * @param  int|string $col position or column name
     * @param  int $rowCount if > 1, fetch $rowCount rows
     * @return string|array
     * @throws RuntimeException if not a SELECT
     * @access public
     */
    public function fetchCol($col = 0, $rowCount = 0)/*# : array */;

    /**
     * Close/free the result set
     *
     * @access public
     */
    public function close();
}
