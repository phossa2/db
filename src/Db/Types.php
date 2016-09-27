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

use Phossa2\Shared\Base\StaticAbstract;

/**
 * Types
 *
 * @package Phossa2\Db
 * @author  Hong Zhang <phossa@126.com>
 * @see     StaticAbstract
 * @version 2.0.0
 * @since   2.0.0 added
 */
class Types extends StaticAbstract
{
    /**#@+
     * Parameter types
     *
     * @var   int
     */

    /**
     * null
     */
    const PARAM_NULL = \PDO::PARAM_NULL;

    /**
     * integer
     */
    const PARAM_INT = \PDO::PARAM_INT;

    /**
     * string
     */
    const PARAM_STR = \PDO::PARAM_STR;

    /**
     * lob
     */
    const PARAM_LOB = \PDO::PARAM_LOB;

    /**
     * statement
     */
    const PARAM_STMT = \PDO::PARAM_STMT;

    /**
     * boolean
     */
    const PARAM_BOOL = \PDO::PARAM_BOOL;

    /**#@-*/

    /**
     * bind parameters
     *
     * @param  mixed value
     * @param  int $suggestType suggested type
     * @return int
     * @access public
     * @static
     */
    public static function guessType(
        $value,
        /*# int */ $suggestType = self::PARAM_STR
    )/*# : int */ {
        if (is_null($value)) {
            return self::PARAM_NULL;
        } elseif (is_int($value)) {
            return self::PARAM_INT;
        } elseif (is_bool($value)) {
            return self::PARAM_BOOL;
        } else {
            return $suggestType;
        }
    }
}
