<?php

namespace Tests;

use OCFram\Managers;
use OCFram\PDOFactory;
use PHPUnit\Framework\TestCase;

class AbstractPDOTestCase extends TestCase
{
    /**
     * @var \PDO
     */
    public static $db;

    /**
     * @var Managers $managers
     */
    public static $managers;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $dsn = "sqlite:" . TEST_DB_PATH;
        PDOFactory::setPdoAddress($dsn);
        self::$db = PDOFactory::getSqliteConnexion();
        self::$managers = new Managers('PDO', self::$db);

        self::executeSqlScript(ACTIONNEURS_SQL_PATH);
        self::executeSqlScript(ACTIONNEURS_DATA_SQL_PATH);
    }

    /**
     * @param string $path
     */
    public static function executeSqlScript($path)
    {
        if (file_exists($path)) {
            $sql = file_get_contents($path);
            self::$db->exec($sql);
        }
    }
}
