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

use Phossa2\Db\Types;
use Phossa2\Db\Message\Message;
use Phossa2\Db\Traits\ConnectTrait;
use Phossa2\Shared\Aware\TagAwareTrait;
use Phossa2\Shared\Base\ObjectAbstract;
use Phossa2\Db\Traits\TransactionTrait;
use Phossa2\Db\Exception\LogicException;
use Phossa2\Db\Traits\ProfilerAwareTrait;
use Phossa2\Shared\Error\ErrorAwareTrait;
use Phossa2\Db\Interfaces\DriverInterface;
use Phossa2\Shared\Aware\TagAwareInterface;
use Phossa2\Db\Exception\NotFoundException;
use Phossa2\Db\Interfaces\StatementInterface;
use Phossa2\Db\Exception\BadMethodCallException;

/**
 * DriverAbstract
 *
 * These methods are from ResultInterface
 *
 * @method bool isSelect()
 * @method int fieldCount()
 * @method int rowCount()
 * @method int affectedRows()
 * @method array fetchAll()
 * @method array fetchRow(int $rowCount)
 * @method array fetchCol(int $col, int $rowCount)
 *
 * @package Phossa2\Db
 * @author  Hong Zhang <phossa@126.com>
 * @see     ObjectAbstract
 * @see     DriverInterface
 * @see     TagAwareInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
abstract class DriverAbstract extends ObjectAbstract implements DriverInterface, TagAwareInterface
{
    use ConnectTrait, TransactionTrait, ErrorAwareTrait, ProfilerAwareTrait, TagAwareTrait;

    /**
     * Statement prototype
     *
     * @var    StatementInterface
     * @access protected
     */
    protected $statement_prototype;

    /**
     * current statement
     *
     * @var    StatementInterface
     * @access protected
     */
    protected $statement;

    /**
     * constructor
     *
     * @param  array $parameters
     * @throws LogicException driver specific extension not loaded
     * @access public
     */
    public function __construct(array $parameters)
    {
        if (!$this->extensionLoaded()) {
            throw new LogicException(
                Message::get(Message::DB_EXTENSION_NOTLOAD, get_class($this)),
                Message::DB_EXTENSION_NOTLOAD
            );
        }
        $this->connect_parameters = $parameters;
    }

    /**
     *
     * @param  string $method
     * @param  array $args
     * @return mixed
     * @throws BadMethodCallException
     * @access public
     */
    public function __call(/*# string */ $method, array $args)
    {
        $result = $this->getResult();
        if (method_exists($result, $method)) {
            return call_user_func_array([$result, $method], $args);
        }

        throw new BadMethodCallException(
            Message::get(Message::MSG_METHOD_NOTFOUND, $method),
            Message::MSG_METHOD_NOTFOUND
        );
    }

    /**
     * {@inheritDoc}
     */
    public function prepare(/*# string */ $sql)/*# : bool */
    {
        if ($this->statement) {
            $this->statement->close();
        }

        // new statement
        $this->statement = clone $this->statement_prototype;
        $this->statement->setDriver($this);

        try {
            return $this->statement->prepare($sql);
        } catch (\Exception $e) {
            return $this->setError($e->getMessage(), $e->getCode());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getStatement()/*# : StatementInterface */
    {
        if (null === $this->statement) {
            throw new NotFoundException(
                Message::get(Message::DB_STMT_NOTPREPARED),
                Message::DB_STMT_NOTPREPARED
            );
        }
        return $this->statement;
    }

    /**
     * {@inheritDoc}
     */
    public function query(
        /*# string */ $sql,
        array $parameters = []
    )/*# : bool */ {
        try {
            return $this->prepare($sql) &&
                $this->statement->execute($parameters);
        } catch (\Exception $e) {
            return $this->setError($e->getMessage(), $e->getCode());
        }
    }

    /**
     * {@inheritDoc}
     */
    public function getResult()/*# : ResultInterface */
    {
        return $this->getStatement()->getResult();
    }

    /**
     * {@inheritDoc}
     */
    public function lastInsertId($name = null)
    {
        if ($this->isConnected()) {
            return $this->realLastId($name);
        }
        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function quote(
        $string,
        /*# int */ $type = Types::PARAM_STR
    )/*# : string */ {
        if ($this->isConnected()) {
            return $this->realQuote($string, Types::guessType($string, $type));
        }
        // default
        return "'" . $string . "'";
    }

    /**
     * Check driver specific extension loaded or not
     *
     * @return bool
     * @access protected
     */
    abstract protected function extensionLoaded()/*# : bool */;

    /**
     * Driver specific last inserted id
     *
     * @param  string|null $name sequence name
     * @return string|null
     * @access protected
     */
    abstract protected function realLastId($name);

    /**
     * The real quote method
     *
     * @param  mixed $string
     * @param  int $type
     * @return string
     * @access protected
     */
    abstract protected function realQuote(
        $string,
        /*# int */ $type
    )/*# : string */;
}
