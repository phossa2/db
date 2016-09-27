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

namespace Phossa2\Db\Driver\Pdo;

use Phossa2\Db\Message\Message;
use Phossa2\Db\Driver\DriverAbstract;
use Phossa2\Db\Exception\LogicException;
use Phossa2\Db\Interfaces\StatementInterface;

/**
 * Driver
 *
 * PDO driver
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
     * @var    \PDO
     * @access protected
     */
    protected $link;

    /**
     * Default PDO attributes
     *
     * @var    array
     * @access protected
     */
    protected $attributes = [
        'PDO::ATTR_ERRMODE' => \PDO::ERRMODE_SILENT,
        'PDO::ATTR_CASE' => \PDO::CASE_NATURAL,
        'PDO::ATTR_ORACLE_NULLS' => \PDO::NULL_NATURAL,
        'PDO::ATTR_DEFAULT_FETCH_MODE' => \PDO::FETCH_ASSOC,
        'PDO::ATTR_EMULATE_PREPARES' => false,
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
        return extension_loaded('PDO');
    }

    /**
     * {@inheritDoc}
     */
    protected function realLastId($name)
    {
        return $this->link->lastInsertId($name);
    }

    /**
     * {@inheritDoc}
     */
    protected function realQuote(
        $string,
        /*# int */ $type
    )/*# : string */ {
        return $this->link->quote($string, $type);
    }

    /**
     * {@inheritDoc}
     */
    protected function realConnect(array $parameters)
    {
        $link = new \PDO(
            $parameters['dsn'],
            isset($parameters['username']) ? $parameters['username'] : 'root',
            isset($parameters['password']) ? $parameters['password'] : null,
            isset($parameters['options']) ? $parameters['options'] : null
        );
        return $link;
    }

    /**
     * Disconnect the \PDO link
     *
     * {@inheritDoc}
     */
    protected function realDisconnect()
    {
    }

    /**
     * {@inheritDoc}
     */
    protected function realPing()/*# : bool */
    {
        try {
            return (bool) $this->link->query('SELECT 1');
        } catch (\Exception $e) {
            return $this->setError($e->getMessage(), $e->getCode());
        }
    }

    /**
     * {@inheritDoc}
     */
    protected function realSetAttribute(/*# string */ $attribute, $value)
    {
        if (is_string($attribute)) {
            $this->checkAttribute($attribute);
            $this->link->setAttribute(constant($attribute), $value);
        } else {
            $this->link->setAttribute($attribute, $value);
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
            return $this->link->getAttribute(constant($attribute));
        } else {
            return $this->link->getAttribute($attribute);
        }
    }

    /**
     * {@inheritDoc}
     */
    protected function realBegin()
    {
        $this->link->beginTransaction();
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    protected function realCommit()
    {
        $this->link->commit();
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    protected function realRollback()
    {
        $this->link->rollBack();
        return $this;
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
