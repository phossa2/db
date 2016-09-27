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
 * ProfilerAwareInterface
 *
 * @package Phossa2\Db
 * @author  Hong Zhang <phossa@126.com>
 * @version 2.0.0
 * @since   2.0.0 added
 */
interface ProfilerAwareInterface
{
    /**
     * Is profiling enabled
     *
     * @return bool
     * @access public
     * @api
     */
    public function isProfiling()/*# : bool */;

    /**
     * Enable profiling
     *
     * @param  bool $flag
     * @return $this
     * @access public
     * @api
     */
    public function enableProfiling(/*# bool */ $flag = true);

    /**
     * Set the profiler
     *
     * @param  ProfilerInterface $profiler
     * @return $this
     * @access public
     * @api
     */
    public function setProfiler(ProfilerInterface $profiler);

    /**
     * Get the profiler, if not set, get the default one
     *
     * @return ProfilerInterface
     * @access public
     * @api
     */
    public function getProfiler()/*# : ProfilerInterface */;
}
