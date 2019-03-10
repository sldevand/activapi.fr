<?php

namespace Tests\Model;

use Entity\Scenario\Scenario;
use Model\Scenario\ScenariosManagerPDO;

/**
 * Class ScenariosManagerPDOTest
 * @package Tests\Model
 */
class ScenariosManagerPDOTest extends AbstractManagerPDOTest
{
    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        if (file_exists(SCENARIOS_SQL_PATH)) {
            $sql = file_get_contents(SCENARIOS_SQL_PATH);
            self::$db->exec($sql);
        }
    }

    /**
     * @dataProvider scenariosProvider
     * @param Scenario $expected
     * @param Scenario $scenario
     * @throws \Exception
     */
    public function testSave($scenario, $expected)
    {
        /** @var ScenariosManagerPDO $manager */
        $manager = new ScenariosManagerPDO(self::$db);
        $manager->save($scenario);
        $persisted = $manager->getUnique($expected->id());

        self::assertEquals($expected, $persisted);
    }

    /**
     * @return Scenario
     */
    public function makeScenario()
    {
        return new Scenario(
            [
                'nom' => 'ScenarioTest'
            ]
        );
    }


    public function scenariosProvider()
    {
        return [
            "firstScenario" => [
                new Scenario(
                    [
                        'nom' => 'ScenarioTest'
                    ]
                ),
                new Scenario(
                    [
                        'id' => '1',
                        'nom' => 'ScenarioTest'
                    ]
                )
            ]
        ];
    }
}
