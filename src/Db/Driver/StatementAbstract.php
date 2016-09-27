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

namespace Phossa2\Db\Driver;

use Phossa2\Db\Message\Message;
use Phossa2\Db\Traits\DriverAwareTrait;
use Phossa2\Shared\Base\ObjectAbstract;
use Phossa2\Db\Interfaces\ResultInterface;
use Phossa2\Db\Exception\RuntimeException;
use Phossa2\Db\Exception\NotFoundException;
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
     * Desctructor
     *
     * @access public
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * {@inheritDoc}
     */
    public function prepare(/*# string */ $sql)/*# : bool */
    {
        // close previous prepared sql if any
        $this->close();

        // prepare sql
        try {
            $res = $this->realPrepare($this->getDriver()->getLink(), $sql);
            if (false !== $res) {
                $this->prepared = $res;
                $this->getDriver()->getProfiler()->setSql($sql);
                return true;
            }
        } catch (\Exception $e) {
            throw new RuntimeException($e->getMessage(), (int) $e->getCode(), $e);
        }
        throw new RuntimeException(
            Message::get(Message::DB_STMT_PREPARE_FAIL, $sql),
            Message::DB_STMT_PREPARE_FAIL
        );
    }

    /**
     * {@inheritDoc}
     */
    public function execute(array $parameters = [])/*# : bool */
    {
        $this->checkPreparation();
        $this->getDriver()->getProfiler()->setParameters($parameters)->startWatch();

        try {
            if ($this->realExecute($parameters)) {
                $result = clone $this->result_prototype;
                $this->result = $result($this->prepared);
                $this->getDriver()->getProfiler()->stopWatch();
                return true;
            }
        } catch (\Exception $e) {
            throw new RuntimeException($e->getMessage(), (int) $e->getCode(), $e);
        }
        throw new RuntimeException(
            Message::get(
                Message::DB_STMT_EXECUTE_FAIL,
                $this->getDriver()->getProfiler()->getSql()
            ),
            Message::DB_STMT_EXECUTE_FAIL
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getResult()/*# : ResultInterface */
    {
        if (null === $this->result) {
            throw new NotFoundException(
                Message::get(Message::DB_STMT_NO_RESULT),
                Message::DB_STMT_NO_RESULT
            );
        }
        return $this->result;
    }

    /**
     * {@inheritDoc}
     */
    public function close()
    {
        if ($this->prepared) {
            // close result
            $this->closeResult();

            // close statement
            $this->realClose($this->prepared);
            $this->prepared = null;
        }
    }

    /**
     * Throw exception if not prepared
     *
     * @throws RuntimeException
     * @access protected
     */
    protected function checkPreparation()
    {
        // must be prepared
        if (null === $this->prepared) {
            throw new RuntimeException(
                Message::get(Message::DB_STMT_NOTPREPARED),
                Message::DB_STMT_NOTPREPARED
            );
        }

        // close any previous resultset
        $this->closeResult();
    }

    /**
     * Close resultset if any
     *
     * @access protected
     */
    protected function closeResult()
    {
        if ($this->result) {
            $this->result->close();
            $this->result = null;
        }
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
     * Close statement
     *
     * @param  mixed prepared low-level statement
     * @access protected
     */
    abstract protected function realClose($stmt);
}
