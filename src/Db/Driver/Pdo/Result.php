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

use Phossa2\Db\Driver\ResultAbstract;

/**
 * Result
 *
 * @package Phossa2\Db
 * @author  Hong Zhang <phossa@126.com>
 * @see     ResultAbstract
 * @version 2.0.0
 * @since   2.0.0 added
 */
class Result extends ResultAbstract
{
    /**
     * @var    \PDOStatement
     * @access protected
     */
    protected $statement;

    /**
     * Invoke to set statement
     *
     * @param  \PDOStatement $statement
     * @return $this
     * @access public
     */
    public function __invoke(\PDOStatement $statement)
    {
        $this->statement = $statement;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function fieldCount()/*# : int */
    {
        return $this->statement->columnCount();
    }

    /**
     * {@inheritDoc}
     */
    public function rowCount()/*# : int */
    {
        return $this->statement->rowCount();
    }

    /**
     * {@inheritDoc}
     */
    public function affectedRows()/*# : int */
    {
        return $this->rowCount();
    }

    /**
     * {@inheritDoc}
     */
    public function close()
    {
        if ($this->statement) {
            $this->statement->closeCursor();
        }
    }

    /**
     * {@inheritDoc}
     */
    protected function realFetchAll()/*# : array */
    {
        return $this->statement->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * {@inheritDoc}
     */
    protected function realFetchRow($rowCount)/*# : array */
    {
        $result = [];
        $count  = 0;
        while ($count++ < $rowCount) {
            $row = $this->statement->fetch(\PDO::FETCH_ASSOC);
            if (false === $row) {
                break;
            }
            $result[] = $row;
        }
        return $result;
    }
}
