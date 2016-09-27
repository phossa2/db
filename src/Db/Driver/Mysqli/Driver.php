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

namespace Phossa2\Db\Driver\Mysqli;

use Phossa2\Db\Message\Message;
use Phossa2\Db\Driver\DriverAbstract;
use Phossa2\Db\Exception\LogicException;
use Phossa2\Db\Interfaces\StatementInterface;

/**
 * Driver
 *
 * Mysqli driver
 *
 * @package Phossa2\Db
 * @author  Hong Zhang <phossa@126.com>
 * @see     DriverAbstract
 * @version 2.0.0
 * @since   2.0.0 added
 */
class Driver extends DriverAbstract
{
    /**
     * the connection link
     *
     * @var    \mysqli
     * @access protected
     */
    protected $link;

    /**
     * Default mysqli attributes
     *
     * @var    array
     * @access protected
     */
    protected $attributes = [
        'MYSQLI_OPT_CONNECT_TIMEOUT' => 300,
        'MYSQLI_OPT_LOCAL_INFILE' => true,
        'MYSQLI_INIT_COMMAND' => '',
    ];

    /**
     * Driver constructor
     *
     * @param  array $connectInfo
     * @param  StatementInterface $statementPrototype
     * @throws InvalidArgumentException if link type not right
     * @throws LogicException driver specific extension not loaded
     * @access public
     */
    public function __construct(
        $connectInfo,
        StatementInterface $statementPrototype = null
    ) {
        parent::__construct($connectInfo);

        // set prototypes
        $this->statement_prototype = $statementPrototype ?: new Statement();
    }

    /**
     * {@inheritDoc}
     */
    protected function extensionLoaded()/*# : bool */
    {
        return extension_loaded('mysqli');
    }

    /**
     * {@inheritDoc}
     */
    protected function realLastId($name)
    {
        return $this->link->insert_id;
    }

    /**
     * {@inheritDoc}
     */
    protected function realQuote(
        $string,
        /*# int */ $type
    )/*# : string */ {
        return '\'' . $this->link->real_escape_string($string) . '\'';
    }

    /**
     * {@inheritDoc}
     */
    protected function realConnect(array $parameters)
    {
        // init
        $link = new \mysqli();
        $link->init();

        // params with defaults
        $p = $this->fixParams($parameters);

        // real connect
        $link->real_connect(
            $p['host'],
            $p['username'],
            $p['password'],
            $p['db'],
            $p['port'],
            $p['socket']
        );

        $this->isConenctFailed($link);
        $this->setCharset($link, $parameters);
        return $link;
    }

    /**
     * Disconnect the \PDO link
     *
     * {@inheritDoc}
     */
    protected function realDisconnect()
    {
        $this->link->close();
    }

    /**
     * {@inheritDoc}
     */
    protected function realPing()/*# : bool */
    {
        return $this->link->ping();
    }

    /**
     * {@inheritDoc}
     */
    protected function realSetAttribute(/*# string */ $attribute, $value)
    {
        if (is_string($attribute)) {
            $this->checkAttribute($attribute);
            $this->link->options(constant($attribute), $value);
        } else {
            $this->link->options($attribute, $value);
        }
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    protected function realGetAttribute(/*# string */ $attribute)
    {
        if (is_string($attribute)) {
            $this->checkAttribute($attribute);
        }
        return null;
    }

    /**
     * {@inheritDoc}
     */
    protected function realBegin()
    {
        $this->link->autocommit(false);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    protected function realCommit()
    {
        $this->link->commit();
        $this->link->autocommit(true);
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    protected function realRollback()
    {
        $this->link->rollback();
        $this->link->autocommit(true);
        return $this;
    }

    /**
     * Fill connect params with defaults
     *
     * @param  array $params
     * @return array
     * @access protected
     */
    protected function fixParams(array $params)/*# : array */
    {
        return array_replace([
            'host' => 'localhost',
            'username' => 'root',
            'password' => null,
            'db' => null,
            'port' => null,
            'socket' => null
        ], $params);
    }

    /**
     *
     * @param  \mysqli $link
     * @throws LogicException if failed
     * @access protected
     */
    protected function isConenctFailed(\mysqli $link)
    {
        if ($link->connect_error) {
            throw new LogicException(
                Message::get(
                    Message::DB_CONNECT_FAIL,
                    $link->connect_errno,
                    $link->connect_error
                ),
                Message::DB_CONNECT_FAIL
            );
        }
    }

    /**
     * Set charset
     *
     * @param  \mysqli $link
     * @param  array $params
     * @access protected
     */
    protected function setCharset(\mysqli $link, array $params)
    {
        if (!empty($params['charset'])) {
            $link->set_charset($params['charset']);
        }
    }

    /**
     * Is attribute defined ?
     *
     * @param  string $attribute
     * @throws LogicException
     * @access protected
     */
    protected function checkAttribute(/*# string */ $attribute)
    {
        if (!defined($attribute)) {
            throw new LogicException(
                Message::get(Message::DB_ATTRIBUTE_UNKNOWN, $attribute),
                Message::DB_ATTRIBUTE_UNKNOWN
            );
        }
    }
}
