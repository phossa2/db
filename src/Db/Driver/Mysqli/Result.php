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
     * the column names
     *
     * @var    array
     * @access protected
     */
    protected $cols;

    /**
     * the column values
     *
     * @var    array
     * @access protected
     */
    protected $vals;

    /**
     * @var    \mysqli_stmt
     * @access protected
     */
    protected $statement;

    /**
     * Invoke to set statement
     *
     * @param  \mysqli_stmt $statement
     * @return $this
     * @access public
     */
    public function __invoke(\mysqli_stmt $statement)
    {
        $this->statement = $statement;
        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function fieldCount()/*# : int */
    {
        return $this->statement->field_count;
    }

    /**
     * {@inheritDoc}
     */
    public function rowCount()/*# : int */
    {
        return $this->statement->num_rows;
    }

    /**
     * {@inheritDoc}
     */
    public function affectedRows()/*# : int */
    {
        return $this->statement->affected_rows;
    }

    /**
     * {@inheritDoc}
     */
    public function close()
    {
        if ($this->statement) {
            $this->statement->free_result();
        }
    }

    /**
     * {@inheritDoc}
     */
    protected function realFetchAll()/*# : array */
    {
        return $this->realFetchRow(100000);
    }

    /**
     * {@inheritDoc}
     */
    protected function realFetchRow($rowCount)/*# : array */
    {
        $count = 0;
        $result = [];
        while ($count++ < $rowCount) {
            $row = $this->getOneRow();
            if (is_array($row)) {
                $result[] = $row;
            } else {
                break;
            }
        }
        return $result;
    }

    /**
     * Get one row of data
     *
     * @return array|false
     * @access protected
     */
    protected function getOneRow()
    {
        if ($this->bindResult()) {
            if ($this->statement->fetch()) {
                $row = [];
                foreach ($this->cols as $i => $col) {
                    $row[$col] = $this->vals[$i];
                }
                return $row;
            }
        }
        return false;
    }

    /**
     * Bind results
     *
     * @return bool
     * @access protected
     */
    protected function bindResult()/*# : bool */
    {
        // get fields first
        if (!$this->getFields()) {
            return false;
        }

        // bind values
        if (null === $this->vals) {
            $this->vals = array_fill(0, count($this->cols), null);

            $refs = [];
            foreach ($this->vals as $i => &$f) {
                $refs[$i] = &$f;
            }
            call_user_func_array([$this->statement, 'bind_result'], $refs);
        }
        return true;
    }

    /**
     * Get fields first
     *
     * @return bool
     * @access protected
     */
    protected function getFields()/*# : bool */
    {
        if (null === $this->cols) {
            $result = $this->statement->result_metadata();
            if (false === $result) {
                return false;
            }

            $this->cols = [];

            // set column name
            foreach ($result->fetch_fields() as $col) {
                $this->cols[] = $col->name;
            }
        }
        return true;
    }
}
