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

use Phossa2\Db\Message\Message;
use Phossa2\Shared\Base\ObjectAbstract;
use Phossa2\Db\Interfaces\ResultInterface;
use Phossa2\Db\Exception\RuntimeException;
use Phossa2\Db\Interfaces\StatementInterface;

/**
 * StatementAbstract
 *
 * @package Phossa2\Db
 * @author  Hong Zhang <phossa@126.com>
 * @see     ObjectAbstract
 * @see     StatementInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
abstract class StatementAbstract extends ObjectAbstract implements StatementInterface
{
    use DriverAwareTrait;

    /**
     * prepared statement
     *
     * @var    mixed
     * @access protected
     */
    protected $prepared;

    /**
     * Result prototype
     *
     * @var    ResultInterface
     * @access protected
     */
    protected $result_prototype;

    /**
     * @var    ResultInterface
     * @access protected
     */
    protected $result;

    /**
     * {@inheritDoc}
     */
    public function prepare(/*# string */ $sql)/*# : bool */
    {
        if ($this->prepared) {
            return true;
        }

        // prepare statement
        $this->prepared = $this->realPrepare(
            $this->getDriver()->getLink(),
            (string) $sql
        );

        // profiling
        $this->getDriver()->getProfiler()->setSql($sql);

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function execute(array $parameters = [])
    {
        if (!$this->prepared) {
            throw new RuntimeException(
                Message::get(Message::DB_STMT_NOTPREPARED),
                Message::DB_STMT_NOTPREPARED
            );
        }

        // close previous statement if any
        $this->closePreviousStatement();

        // start time
        $time = microtime(true);

        $result = clone $this->result_prototype;
        $this->result($this->prepared);
        $this->realExecute($parameters);

        // profiling
        $this->getDriver()->getProfiler()
            ->setParameters($parameters)
            ->setExecutionTime(microtime(true) - $time);

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function getResult()/*# : ResultInterface */
    {
        return $this->result;
    }

    /**
     * Close previous prepared statement for this driver
     *
     * @return $this
     * @access protected
     */
    protected function closePreviousStatement()
    {
        static $previous = [];

        $id = spl_object_hash($this->getDriver());
        if (isset($previous[$id]) && $previous[$id] !== $this->prepared) {
            $this->realClose($previous[$id]);
        }
        $previous[$id] = $this->prepared;

        return $this;
    }

    /**
     * Driver specific prepare statement
     *
     * @param  mixed $link db link resource
     * @param  string $sql
     * @return mixed
     * @throws RuntimeException
     * @access protected
     */
    abstract protected function realPrepare($link, /*# string */ $sql);

    /**
     * Driver specific statement execution
     *
     * @param  array $parameters
     * @return bool
     * @throws RuntimeException
     * @access protected
     */
    abstract protected function realExecute(array $parameters)/*# : bool */;

    /**
     * Close statement's result set
     *
     * @param  mixed prepared low-level statement
     * @access protected
     */
    abstract protected function realClose($stmt);
}
