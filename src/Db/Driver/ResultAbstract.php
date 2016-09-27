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

use Phossa2\Db\Message\Message;
use Phossa2\Shared\Base\ObjectAbstract;
use Phossa2\Db\Interfaces\ResultInterface;
use Phossa2\Db\Exception\RuntimeException;

/**
 * ResultAbstract
 *
 * @package Phossa2\Db
 * @author  Hong Zhang <phossa@126.com>
 * @see     ObjectAbstract
 * @see     ResultInterface
 * @version 2.0.0
 * @since   2.0.0 added
 */
abstract class ResultAbstract extends ObjectAbstract implements ResultInterface
{
    /**
     * Fetched already
     *
     * @var    bool
     * @access protected
     */
    protected $fetched = false;

    /**
     * Desctructor
     *
     * @access public
     */
    public function __destruct()
    {
        $this->close();
    }

    /**
     * {@inheritDoc}
     */
    public function isSelect()/*# : bool */
    {
        return 0 !== $this->fieldCount();
    }

    /**
     * {@inheritDoc}
     */
    public function fetchAll()/*# : array */
    {
        $this->exceptionIfNotSelect();
        $this->exceptionIfFetchedAlready();
        $this->fetched = true;
        return $this->realFetchAll();
    }

    /**
     * {@inheritDoc}
     */
    public function fetchRow(/*# int */ $rowCount = 1)/*# : array */
    {
        $this->exceptionIfNotSelect();
        $this->exceptionIfFetchedAlready();
        $this->fetched = true;
        return $this->realFetchRow($rowCount);
    }

    /**
     * {@inheritDoc}
     */
    public function fetchCol($col = 0, $rowCount = 0)/*# : array */
    {
        $rows = $rowCount ? $this->fetchRow($rowCount) : $this->fetchAll();
        $cols = [];
        foreach ($rows as $row) {
            if (isset($row[$col])) {
                $cols[] = $row[$col];
            }
        }
        return $cols;
    }

    /**
     * Throw exception if not select query
     *
     * @throws RuntimeException if not a select query
     * @access protected
     */
    protected function exceptionIfNotSelect()
    {
        if (!$this->isSelect()) {
            throw new RuntimeException(
                Message::get(Message::DB_RESULT_NOT_SELECT),
                Message::DB_RESULT_NOT_SELECT
            );
        }
    }

    /**
     * Throw exception if fetched already
     *
     * @throws RuntimeException if fetched already
     * @access protected
     */
    protected function exceptionIfFetchedAlready()
    {
        if ($this->fetched) {
            throw new RuntimeException(
                Message::get(Message::DB_RESULT_FETCHED),
                Message::DB_RESULT_FETCHED
            );
        }
    }

    /**
     * Driver fetch all
     *
     * @return array
     * @access protected
     */
    abstract protected function realFetchAll()/*# : array */;

    /**
     * Driver fetch row
     *
     * @param  int $rowCount number of rows to fetch
     * @return array
     * @access protected
     */
    abstract protected function realFetchRow($rowCount)/*# : array */;
}
