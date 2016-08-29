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

use Phossa2\Db\Exception\RuntimeException;
use Phossa2\Db\Interfaces\TransactionInterface;
use Phossa2\Db\Message\Message;

/**
 * TransactionTrait
 *
 * Implementation of TransactionInterface
 *
 * @package Phossa2\Db
 * @author  Hong Zhang <phossa@126.com>
 * @see     TransactionInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
trait TransactionTrait
{
    /**
     * In transaction or not
     *
     * @var    bool
     * @access protected
     */
    protected $transaction = false;

    /**
     * {@inheritDoc}
     */
    public function inTransaction()/*# : bool */
    {
        return $this->transaction;
    }

    /**
     * {@inheritDoc}
     */
    public function begin()
    {
        $this->connect();
        $this->transaction = true;
        $this->realBegin();
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function commit()
    {
        if ($this->isConnected()) {
            $this->realCommit();
        }
        $this->transaction = false;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function rollback()
    {
        if ($this->isConnected()) {
            if (!$this->inTransaction()) {
                throw new RuntimeException(
                    Message::get(Message::DB_TRANSACTION_NOTIN),
                    Message::DB_TRANSACTION_NOTIN
                );
            }
            $this->realRollback();
        }
        $this->transaction = false;
        return $this;
    }

    /**
     * Driver specific begin transaction
     *
     * @access protected
     */
    abstract protected function realBegin();

    /**
     * Driver specific commit
     *
     * @access protected
     */
    abstract protected function realCommit();

    /**
     * Driver specific rollback
     *
     * @access protected
     */
    abstract protected function realRollback();

    /* from other traits */
    abstract public function connect();
    abstract public function isConnected()/*# : bool */;
}
