# phossa2/db
[![Build Status](https://travis-ci.org/phossa2/db.svg?branch=master)](https://travis-ci.org/phossa2/db)
[![Code Quality](https://scrutinizer-ci.com/g/phossa2/db/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/phossa2/db/)
[![Code Climate](https://codeclimate.com/github/phossa2/db/badges/gpa.svg)](https://codeclimate.com/github/phossa2/db)
[![PHP 7 ready](http://php7ready.timesplinter.ch/phossa2/db/master/badge.svg)](https://travis-ci.org/phossa2/db)
[![HHVM](https://img.shields.io/hhvm/phossa2/db.svg?style=flat)](http://hhvm.h4cc.de/package/phossa2/db)
[![Latest Stable Version](https://img.shields.io/packagist/vpre/phossa2/db.svg?style=flat)](https://packagist.org/packages/phossa2/db)
[![License](https://img.shields.io/:license-mit-blue.svg)](http://mit-license.org/)

**phossa2/db** is a PHP db connection management library which handles the
interaction with db.

It requires PHP 5.4, supports PHP 7.0+ and HHVM. It is compliant with [PSR-1][PSR-1],
[PSR-2][PSR-2], [PSR-3][PSR-3], [PSR-4][PSR-4], and the proposed [PSR-5][PSR-5].

[PSR-1]: http://www.php-fig.org/psr/psr-1/ "PSR-1: Basic Coding Standard"
[PSR-2]: http://www.php-fig.org/psr/psr-2/ "PSR-2: Coding Style Guide"
[PSR-3]: http://www.php-fig.org/psr/psr-3/ "PSR-3: Logger Interface"
[PSR-4]: http://www.php-fig.org/psr/psr-4/ "PSR-4: Autoloader"
[PSR-5]: https://github.com/phpDocumentor/fig-standards/blob/master/proposed/phpdoc.md "PSR-5: PHPDoc"

Features
---
- Simple interface. Nothing you don't need.

- Multiple db platform/driver support, currently PDO (all PDO drivers) and
  Mysqli.

- Handles multiple connections through driver manager

  - Round-robin load balancing

    Multiple db connections are used in round-robin fashion and weighting factor
    (1-10) supported. Each connection is monitored (pinged).

  - driver tagging, so user can tag different db connection as 'reader' or
    'writer' etc.

- Easy profiling, get each executed sql and its execution time.

- Secure. All SQL executed through prepare/execute in low-level drivers.

Installation
---
Install via the `composer` utility.

```bash
composer require "phossa2/db"
```

or add the following lines to your `composer.json`

```json
{
    "require": {
       "phossa2/db": "2.*"
    }
}
```

Usage
---
- Driver

  - DDL

    ```php
    use Phossa2\Db\Driver\Pdo\Driver as Pdo_Driver;

    $db = new Pdo_Driver([
        'dsn' => 'mysql:dbname=test;host=127.0.0.1;charset=utf8'
    ]);

    // simple delete
    if ($db->query("DELETE FROM test WHERE id < 10")) {
        echo sprintf("%d records deleted", $db->affectedRows()) . \PHP_EOL;
    } else {
        echo $db->getError() . \PHP_EOL;
    }

    // with parameters
    if ($db->query("INSERT INTO test (val) VALUES (?)", [ 100 ])) {
        echo sprintf("last id is %d", $db->lastInsertId()) . \PHP_EOL;
    } else {
        echo $db->getError() . \PHP_EOL;
    }
    ```

  - SELECT

    ```php
    // simple select
    if ($db->query("SELECT * FROM test WHERE id < 10")) {
        $rows = $db->getResult()->fetchAll();
    } else {
        echo $db->getError() . \PHP_EOL;
    }

    // fetch first 5 rows
    if ($db->query("SELECT * FROM test WHERE id > ? LIMIT ?", [10, 20])) {
        $rows = $db->getResult()->fetchRow(5);
    }

    // fetch first field
    if ($db->query("SELECT id, name FROM test WHERE id < :id", ['id' => 10])) {
        $cols = $db->getResult()->fetchCol('id');
    }
    ```

- Statment

  `Statement` is returned after `$db->prepare()`.

  ```php
  // PREPARE using prepare()
  if ($db->prepare("SELECT * FROM test WHERE id < :id")) {
      $stmt = $db->getStatement();
      if ($stmt->execute(['id' => 10])) {
          $rows = $stmt->getResult()->fetchAll();
      }
  } else {
      echo $db->getError() . \PHP_EOL;
  }
  ```

- Result

  `Result` is returned by `$db->getResult()` or `$stmt->getResult()`

  ```php
  if ($db->query('SELECT * FROM test')) {
      // SELECT
      if ($db->getResult()->isSelect()) {
          // get fields count
          $fieldCount = $db->getResult()->fieldCount();
          // row count
          $rowCount = $db->getResult()->rowCount();

      // DDL
      } else {
          $affectedRows = $db->getResult()->affectedRows();
      }
  }
  ```

Mysqli
---

Mysqli driver is also supported.

```php
use Phossa2\Db\Driver\Mysqli\Driver as Mysqli_Driver;

$db = new Mysqli_Driver([
    'db' => 'mysql',
    'host' => '127.0.0.1',
    'charset' => 'utf8'
]);

// simple delete
if ($db->query("DELETE FROM test WHERE id < ?", [10])) {
    echo sprintf("%d records deleted", $db->affectedRows()) . \PHP_EOL;
} else {
    echo $db->getError() . \PHP_EOL;
}
```

**Note**: named parameters are not supported in Mysqli driver.

Driver manager
---
Driver manager manages multiple db connections. Weighting factor `N` means add
one driver virtually N times. Adding driver *A* with factor 5 and adding driver
*B* with factor 1 into the pool, means when calling `getDriver()`, user will
get *A* five times vs *B* for one time.

```
// dbwriter 1
$db1 = (new Phossa2\Db\Driver\Pdo\Driver($conf1))->addTag('RW');

// dbreader 2
$db2 = (new Phossa2\Db\Driver\Pdo\Driver($conf2))->addTag('RO');

// dbreader 3
$db3 = (new Phossa2\Db\Driver\Pdo\Driver($conf3))->addTag('RO');

// db manager
$dbm = (new Phossa2\Db\Manager\Manager())
    ->addDriver($db1, 1)    // writable connection with factor 1
    ->addDriver($db2, 5)    // read_only, factor 5
    ->addDriver($db3, 5)    // read_only, factor 5

// get a db connect, no matter writer or reader
$db = $dbm->getDriver();

// get a readonly driver
$db = $dbm->getDriver('RO');
```

SQL profiling
---
Get the executed SQL and its execution time.

```php
// init driver
$db = new Phossa2\Db\Driver\Pdo\Driver($conf);

// enable profiling
$db->enableProfiling();

// execute a DELETE
$db->query("DELETE FROM test WHERE test_id > 10");

// get sql
$sql = $db->getProfiler()->getSql();
$time = $db->getProfiler()->getExecutionTime();
```

Method overloading
---
Methods from `Phossa2\Db\Interfaces\ResultInterface` can be accessed through
the db driver after successful execution of a query.

```php
if ($db->query('SELECT * FROM test')) {
    // normally is $db->getResult()->fetchAll()
    $rows = $db->fetchAll();
}
```

Change log
---

Please see [CHANGELOG](CHANGELOG.md) from more information.

Testing
---

```bash
$ composer test
```

Contributing
---

Please see [CONTRIBUTE](CONTRIBUTE.md) for more information.

Dependencies
---

- PHP >= 5.4.0

- phossa2/shared >= 2.0.25

License
---

[MIT License](http://mit-license.org/)
