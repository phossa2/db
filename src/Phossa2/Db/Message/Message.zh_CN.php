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

use Phossa2\Db\Message\Message;

/*
 * Provide zh_CN translation
 *
 * @package Phossa2\Db
 * @author  Hong Zhang <phossa@126.com>
 * @version 2.0.0
 * @since   2.0.0 added
 */
return [
    Message::DB_DRIVER_NOTFOUND => '未发现匹配的数据库驱动',
    Message::DB_DRIVER_NOTSET => '还未设置数据库驱动',
    Message::DB_CONNECT_MISSING => '数据库连接参数未定义',
    Message::DB_CONNECT_FAIL => '数据库连接失败',
    Message::DB_TRANSACTION_NOTIN => '不在数据交易状态',
    Message::DB_EXTENSION_NOTLOAD => '数据库驱动 "%s" 的扩展没有装载',
    Message::DB_STMT_NOTPREPARED => '数据库执行语句还未预处理',
];
