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

namespace Phossa2\Db;

use Phossa2\Shared\Base\ObjectAbstract;
use Phossa2\Db\Traits\DriverAwareTrait;
use Phossa2\Db\Interfaces\ProfilerInterface;

/**
 * Profiler
 *
 * @package Phossa2\Db
 * @author  Hong Zhang <phossa@126.com>
 * @see     ObjectAbstract
 * @see     ProfilerInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
class Profiler extends ObjectAbstract implements ProfilerInterface
{
    use DriverAwareTrait;

    /**
     * Current executed SQL
     *
     * @var    string
     * @access protected
     */
    protected $sql = '';

    /**
     * Parameters cache
     *
     * @var    array
     * @access protected
     */
    protected $params = [];

    /**
     * Execution time
     *
     * @var    float
     * @access protected
     */
    protected $execution_time = 0.0;

    /**
     * {@inheritDoc}
     */
    public function setSql(/*# string */ $sql)
    {
        // init
        $this->sql = $sql;
        $this->params = [];
        $this->execution_time = 0.0;

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function setParameters(array $parameters)
    {
        $this->params = $parameters;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getSql()/*# : string */
    {
        if (!empty($this->params) && $this->hasDriver()) {
            return $this->replaceParamInSql();
        } else {
            return $this->sql;
        }
    }

    /**
     * {@inheritDoc}
     */
    public function startWatch()
    {
        $this->execution_time = microtime(true);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function stopWatch()
    {
        $this->execution_time = microtime(true) - $this->execution_time;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getExecutionTime()/*# : float */
    {
        return $this->execution_time;
    }

    /**
     * Replace params in the SQL
     *
     * @return string
     * @access protected
     */
    protected function replaceParamInSql()/*# : string */
    {
        $count = 0;
        $params = $this->params;
        return preg_replace_callback(
            '/\?|\:\w+/',
            function ($m) use ($count, $params) {
                if ('?' === $m[0]) {
                    $res = $params[$count++];
                } else {
                    $res = isset($params[$m[0]]) ? $params[$m[0]] :
                        $params[substr($m[0], 1)];
                }
                return $this->getDriver()->quote($res);
            },
            $this->sql
        );
    }
}
