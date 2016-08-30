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
use Phossa2\Db\Exception\LogicException;
use Phossa2\Db\Interfaces\ConnectInterface;

/**
 * ConnectTrait
 *
 * Implementation of ConnectInterface
 *
 * @package Phossa2\Db
 * @author  Hong Zhang <phossa@126.com>
 * @see     ConnectInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
trait ConnectTrait
{
    /**
     * the connection link
     *
     * @var    mixed
     * @access protected
     */
    protected $link;

    /**
     * connect parameters
     *
     * @var    array
     * @access protected
     */
    protected $connect_parameters = [];

    /**
     * connection attributes
     *
     * @var    array
     * @access protected
     */
    protected $attributes = [];

    /**
     * {@inheritDoc}
     */
    public function connect()
    {
        if ($this->isConnected()) {
            return $this;
        }

        $this->realConnect($this->connect_parameters);

        if (!$this->isConnected()) {
            throw new LogicException(
                Message::get(Message::DB_CONNECT_FAIL),
                Message::DB_CONNECT_FAIL
            );
        }
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function disconnect()
    {
        if ($this->isConnected()) {
            $this->realDisconnect();
            $this->link = null;
        }
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function isConnected()/*# : bool */
    {
        return null !== $this->link;
    }

    /**
     * {@inheritDoc}
     */
    public function ping()/*# : bool */
    {
        if ($this->isConnected()) {
            return $this->realPing();
        }
        return false;
    }

    /**
     * {@inheritDoc}
     */
    public function getLink()
    {
        $this->connect();
        return $this->link;
    }

    /**
     * {@inheritDoc}
     */
    public function setAttribute(/*# string */ $attribute, $value)
    {
        $this->attributes[$attribute] = $value;
        if ($this->isConnected()) {
            $this->realSetAttribute($attribute, $value);
        }
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function getAttribute(/*# string */ $attribute)
    {
        // current attribute
        $curr = isset($this->attributes[$attribute]) ?
            $this->attributes[$attribute] : null;

        if ($this->isConnected()) {
            $real = $this->realGetAttribute($attribute);
            return $real ?: $curr;
        } else {
            return $curr;
        }
    }

    /**
     * Driver specific connect
     *
     * @param  array $parameters
     * @throws LogicException
     * @access protected
     */
    abstract protected function realConnect(array $parameters);

    /**
     * Driver specific disconnect
     *
     * @access protected
     */
    abstract protected function realDisconnect();

    /**
     * Driver related ping
     *
     * @return bool
     * @access protected
     */
    abstract protected function realPing()/*# : bool */;

    /**
     * Set connection specific attribute
     *
     * @param  string attribute
     * @param  mixed $value
     * @throws LogicException if attribute unknown
     * @access protected
     */
    abstract protected function realSetAttribute(
        /*# string */ $attribute,
        $value
    );

    /**
     * Get connection specific attribute
     *
     * @param  string attribute
     * @return mixed|null
     * @throws LogicException if attribute unknown
     * @access protected
     */
    abstract protected function realGetAttribute(/*# string */ $attribute);
}
