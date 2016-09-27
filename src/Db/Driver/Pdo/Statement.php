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

use Phossa2\Db\Types;
use Phossa2\Db\Driver\StatementAbstract;
use Phossa2\Db\Interfaces\ResultInterface;

/**
 * Statement
 *
 * PDO driver statement
 *
 * @package Phossa2\Db
 * @author  Hong Zhang <phossa@126.com>
 * @see     StatementAbstract
 * @version 2.0.0
 * @since   2.0.0 added
 */
class Statement extends StatementAbstract
{
    /**
     * Constructor
     *
     * @param  ResultInterface $resultPrototype
     * @access public
     */
    public function __construct(ResultInterface $resultPrototype = null)
    {
        $this->result_prototype = $resultPrototype ?: new Result();
    }

    /**
     * {@inheritDoc}
     */
    protected function realPrepare($link, /*# string */ $sql)
    {
        /* @var $link \PDO */
        return $link->prepare($sql);
    }

    /**
     * {@inheritDoc}
     */
    protected function realExecute(array $parameters)/*# : bool */
    {
        /* @var $stmt \PDOStatement */
        $stmt = $this->prepared;

        // bind parameters
        if (!empty($parameters) &&
            !$this->bindParameters($stmt, $parameters)
        ) {
            return false;
        }

        // execute
        return $stmt->execute();
    }

    /**
     * {@inheritDoc}
     */
    protected function realClose($stmt)
    {
    }

    /**
     * bind parameters
     *
     * @param  \PDOStatement $stmt
     * @param  array $parameters
     * @return bool
     * @access protected
     */
    protected function bindParameters(
        \PDOStatement $stmt,
        array $parameters
    )/*# : bool */ {
        foreach ($parameters as $name => &$value) {
            $type  = Types::guessType($value);
            $param = $this->fixParam($name);
            if (false === $stmt->bindParam($param, $value, $type)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Fix param name
     *
     * @param  mixed $name
     * @return string
     * @access protected
     */
    protected function fixParam($name)/*# : string */
    {
        return is_int($name) ?
            ($name + 1) :
            ($name[0] === ':' ? $name : (':' . $name));
    }
}
