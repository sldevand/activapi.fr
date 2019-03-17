<?php

namespace Tests;

use OCFram\PDOFactory;
use PHPUnit\Framework\TestCase;

class AbstractPDOTestCase extends TestCase
{
    /**
     * @var \PDO
     */
    public static $db;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $dsn = "sqlite:" . TEST_DB_PATH;
        PDOFactory::setPdoAddress($dsn);
        self::$db = PDOFactory::getSqliteConnexion();
    }
}
