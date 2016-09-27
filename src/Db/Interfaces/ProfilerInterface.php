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
 * ProfilerInterface
 *
 * @package Phossa2\Db
 * @author  Hong Zhang <phossa@126.com>
 * @see     DriverAwareInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
interface ProfilerInterface extends DriverAwareInterface
{
    /**
     * Set the executing SQL
     *
     * @param  string
     * @return $this
     * @access public
     * @api
     */
    public function setSql(/*# string */ $sql);

    /**
     * Set the parameters
     *
     * @param  array
     * @return $this
     * @access public
     * @api
     */
    public function setParameters(array $parameters);

    /**
     * Get the executed SQL
     *
     * @return string
     * @access public
     * @api
     */
    public function getSql()/*# : string */;

    /**
     * Set start time
     *
     * @return $this
     * @access public
     * @api
     */
    public function startWatch();

    /**
     * Set stop time
     *
     * @return $this
     * @access public
     * @api
     */
    public function stopWatch();

    /**
     * Get execution time
     *
     * @return float $time
     * @access public
     * @api
     */
    public function getExecutionTime()/*# : float */;
}
