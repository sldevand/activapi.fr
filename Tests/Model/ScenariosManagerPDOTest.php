<?php

namespace Tests\Model;

use Entity\Actionneur;
use Entity\Scenario;
use Model\ScenariosManagerPDO;
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
        PDOFactory::setPdoAddress('sqlite:C:\wamp64\www\database\releves.db');
        self::$db = PDOFactory::getSqliteConnexion();
    }

    public function testSave()
    {
        /** @var ScenariosManagerPDO $manager */
        $manager = new ScenariosManagerPDO(self::$db);

        $actionneurs = $this->makeActionneurs();
        $scenario = $this->makeScenario($actionneurs);

        $manager->save($scenario);

        $persisted = $manager->getScenarioByName($scenario->nom());

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
     * @param Actionneur[] $actionneurs
     * @return Scenario
     */
    public function makeScenario($actionneurs)
    {
        return new Scenario(
            [
                'id' => '1',
                'scenarioid' => '1',
                'nom' => 'ScenarioTest',
                'actionneurs' => $actionneurs
            ]
        );
    }
}
