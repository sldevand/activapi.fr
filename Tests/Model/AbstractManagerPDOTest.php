<?php

namespace Tests\Model;

use OCFram\PDOFactory;
use PHPUnit\Framework\TestCase;
use Tests\Api\ManagerPDOInterfaceTest;

/**
 * Class AbstractManagerPDOTest
 * @package Tests\Model
 */
abstract class AbstractManagerPDOTest extends TestCase implements ManagerPDOInterfaceTest
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
