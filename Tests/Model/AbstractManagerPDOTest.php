<?php

namespace Tests\Model;

use OCFram\PDOFactory;
use PHPUnit\Framework\TestCase;

/**
 * Class AbstractManagerPDOTest
 * @package Tests\Model
 */
abstract class AbstractManagerPDOTest extends TestCase
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

        if (file_exists(TEST_INIT_SQL_PATH)) {
            $sql = file_get_contents(TEST_INIT_SQL_PATH);
            self::$db->exec($sql);
        }
    }
}
