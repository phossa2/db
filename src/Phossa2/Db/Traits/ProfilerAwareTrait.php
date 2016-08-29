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

use Phossa2\Db\Profiler;
use Phossa2\Db\Interfaces\ProfilerInterface;
use Phossa2\Db\Interfaces\ProfilerAwareInterface;

/**
 * ProfilerAwareTrait
 *
 * @package Phossa2\Db
 * @author  Hong Zhang <phossa@126.com>
 * @see     ProfilerAwareInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
trait ProfilerAwareTrait
{
    /**
     * the profiler
     *
     * @var    ProfilerInterface
     * @access protected
     */
    protected $profiler;

    /**
     * @var    bool
     * @access protected
     */
    protected $profiling_enabled = false;

    /**
     * {@inheritDoc}
     */
    public function isProfiling()/*# : bool */
    {
        return $this->profiling_enabled;
    }

    /**
     * {@inheritDoc}
     */
    public function enableProfiling(/*# bool */ $flag = true)
    {
        $this->profiling_enabled = (bool) $flag;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setProfiler(ProfilerInterface $profiler)
    {
        $this->profiler = $profiler;
        $this->profiler->setDriver($this);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getProfiler()/*# : ProfilerInterface */
    {
        if (is_null($this->profiler)) {
            $this->setProfiler(new Profiler());
        }
        return $this->profiler;
    }
}
