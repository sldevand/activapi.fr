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
        self::dropandCreateTables();
    }

    public static function dropandCreateTables()
    {
        if (file_exists(SCENARIOS_SQL_PATH)) {
            $sql = file_get_contents(SCENARIOS_SQL_PATH);
            self::$db->exec($sql);
        }
    }

    /**
     * @dataProvider scenariosSaveProvider
     * @param Scenario $expected
     * @param Scenario $scenario
     * @throws \Exception
     */
    public function testSave($scenario, $expected)
    {
        $manager = $this->getManager();
        $manager->save($scenario);
        $persisted = $manager->getUnique($expected->id());
        self::assertEquals($expected, $persisted);
    }

    /**
     * @dataProvider scenariosGetAllProvider
     * @param Scenario[] $expected
     * @param Scenario[] $scenarios
     * @throws \Exception
     */
    public function testGetAll($scenarios, $expected)
    {
        self::dropandCreateTables();
        $manager = $this->getManager();
        foreach ($scenarios as $scenario) {
            $manager->save($scenario);
        }
        $persisted = $manager->getAll();
        self::assertEquals($expected, $persisted);
    }

    /**
     * @dataProvider scenarioDeleteProvider
     * @param Scenario $expected
     * @param Scenario $scenario
     * @throws \Exception
     */
    public function testDelete($scenario, $expected)
    {
        self::dropandCreateTables();

        $manager = $this->getManager();
        $manager->save($scenario);
        $persisted = $manager->getUnique($expected->id());
        self::assertEquals($expected, $persisted);
        $manager->delete($expected->id());
        $persisted = $manager->getUnique($expected->id());
        self::assertNotEquals($expected, $persisted);
    }

    /**
     * @return array
     */
    public function scenariosSaveProvider()
    {
        return [
            "createScenario" => [
                $this->makeScenario('ScenarioTest'),
                $this->makeScenario('ScenarioTest', 1)
            ],
            "updateScenario" => [
                $this->makeScenario('ScenarioTest2', 1),
                $this->makeScenario('ScenarioTest2', 1)
            ]
        ];
    }

    /**
     * @return array
     */
    public function scenariosGetAllProvider()
    {
        return [
            "createScenario" => [
                [
                    $this->makeScenario('TestScenario1'),
                    $this->makeScenario('TestScenario2'),
                    $this->makeScenario('TestScenario3'),
                    $this->makeScenario('TestScenario4')
                ],
                [
                    $this->makeScenario('TestScenario1', '1'),
                    $this->makeScenario('TestScenario2', '2'),
                    $this->makeScenario('TestScenario3', '3'),
                    $this->makeScenario('TestScenario4', '4')
                ]
            ]
        ];
    }

    /**
     * @return array
     */
    public function scenarioDeleteProvider()
    {
        return [
            "deleteScenario" => [
                $this->makeScenario('ScenarioTest'),
                $this->makeScenario('ScenarioTest', 1)
            ]
        ];
    }

    /**
     * @param $nom
     * @param null | int $id
     * @return Scenario
     */
    public function makeScenario($nom, $id = null)
    {
        return new Scenario(
            [
                'id' => $id,
                'nom' => $nom
            ]
        );
    }

    /**
     * @return ScenariosManagerPDO
     */
    public function getManager()
    {
        /** @var ScenariosManagerPDO $manager */
        return new ScenariosManagerPDO(self::$db);
    }
}
