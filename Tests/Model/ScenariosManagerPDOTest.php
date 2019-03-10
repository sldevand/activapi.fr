<?php

namespace Tests\Model;

use Entity\Actionneur;
use Entity\Scenario\Scenario;
use Model\Scenario\ScenariosManagerPDO;
use OCFram\PDOFactory;
use PHPUnit\Framework\TestCase;

/**
 * Class ScenariosManagerPDOTest
 * @package Tests\Model
 */
class ScenariosManagerPDOTest extends TestCase
{
    /**
     * @var \PDO
     */
    public static $db;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        $command = SQLITE_PATH . " " . TEST_DB_PATH;
        $dsn = "sqlite:" . TEST_DB_PATH;

        PDOFactory::setPdoAddress($dsn);
        self::$db = PDOFactory::getSqliteConnexion();

        if (file_exists(TEST_INIT_SQL_PATH)) {
            $sql = file_get_contents(TEST_INIT_SQL_PATH);
            self::$db->exec($sql);
        }
    }

    /**
     * @throws \Exception
     */
    public function testSave()
    {
        /** @var ScenariosManagerPDO $manager */
        $manager = new ScenariosManagerPDO(self::$db);
        $scenario = $this->makeScenario();
        $manager->save($scenario);
        $persisted = $manager->getUnique(1, $scenario);

        var_dump($persisted);

        self::assertEquals($scenario, $persisted);
    }

    /**
     * @param $nom
     * @return Actionneur
     */
    public function makeActionneur($nom)
    {
        return new Actionneur([
            'nom' => $nom
        ]);
    }

    /**
     * @return Actionneur[]
     */
    public function makeActionneurs()
    {
        return [
            $this->makeActionneur('Salon'),
            $this->makeActionneur('Chambre'),
            $this->makeActionneur('Cuisine'),
            $this->makeActionneur('Sdb')
        ];
    }

    /**
     * @return Scenario
     */
    public function makeScenario()
    {
        return new Scenario(
            [
                'id' => '1',
                'nom' => 'ScenarioTest'
            ]
        );
    }
}
