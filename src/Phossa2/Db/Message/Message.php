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

namespace Phossa2\Db\Message;

use Phossa2\Shared\Message\Message as BaseMessage;

/**
 * Message class for Phossa2\Db
 *
 * @package Phossa2\Db
 * @author  Hong Zhang <phossa@126.com>
 * @see     \Phossa2\Shared\Message\Message
 * @version 2.0.0
 * @since   2.0.0 added
 */
class Message extends BaseMessage
{
    /*
     * Matching db driver not found
     */
    const DB_DRIVER_NOTFOUND = 1608291000;

    /*
     * Db driver not set yet
     */
    const DB_DRIVER_NOTSET = 1608291001;

    /*
     * Db connect parameters missing
     */
    const DB_CONNECT_MISSING = 1608291010;

    /*
     * Db connect failed
     */
    const DB_CONNECT_FAIL = 1608291011;

    /*
     * Not in db transaction mode
     */
    const DB_TRANSACTION_NOTIN = 1608291020;

    /*
     * Driver extension not loaded for "%s"
     */
    const DB_EXTENSION_NOTLOAD = 1608291030;

    /*
     * Statement not prepared yet
     */
    const DB_STMT_NOTPREPARED = 1608291040;

    /**
     * {@inheritDoc}
     */
    protected static $messages = [
        self::DB_DRIVER_NOTFOUND => 'Matching db driver not found',
        self::DB_DRIVER_NOTSET => 'Db driver not set yet',
        self::DB_CONNECT_MISSING => 'Db connect parameters missing',
        self::DB_CONNECT_FAIL => 'Db connect failed',
        self::DB_TRANSACTION_NOTIN => 'Not in db transaction mode',
        self::DB_EXTENSION_NOTLOAD => 'Driver extension not loaded for "%s"',
        self::DB_STMT_NOTPREPARED => 'Statement not prepared yet',
    ];
}
