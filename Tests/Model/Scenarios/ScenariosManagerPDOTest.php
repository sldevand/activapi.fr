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
        self::dropAndCreateTables();
    }

    public static function dropAndCreateTables()
    {
        self::executeSqlScript(SCENARIOS_SQL_PATH);
        self::executeSqlScript(SCENARIOS2_SQL_PATH);
    }

    /**
     * @dataProvider saveProvider
     * @param Scenario $expected
     * @param Scenario $scenario
     * @throws \Exception
     */
    public function testSave($scenario, $expected)
    {
        self::dropAndCreateTables();
        $this->fixtureActions();
        $this->fixtureSequences();

        $scenarioManager = $this->getManager();

        $scenarioId = $scenarioManager->save($scenario);
        $persisted = $scenarioManager->getUnique($expected->id());
        self::assertEquals($expected, $persisted);
        self::assertTrue($scenarioId == 1);

        //Update Test
        $expected->setId(1);
        $expected->setNom('Test2');
        $scenario->setId($scenarioId);
        $scenario->setNom('Test2');
        $scenarioManager->save($scenario);
        $persisted = $scenarioManager->getUnique($expected->id());

        self::assertEquals($expected, $persisted);
    }

    /**
     * @dataProvider getAllProvider
     * @param Scenario[] $expected
     * @param Scenario[] $scenarios
     * @throws \Exception
     */
    public function testGetAll($scenarios, $expected)
    {
        self::dropAndCreateTables();
        $this->fixtureActions();
        $this->fixtureSequences();

        $manager = $this->getManager();
        foreach ($scenarios as $scenario) {
            $manager->save($scenario);
        }
        $persisted = $manager->getAll();
        self::assertEquals($expected, $persisted);
    }

    /**
     * @dataProvider getVisibleProvider
     * @param Scenario[] $expected
     * @param Scenario[] $scenarios
     * @throws \Exception
     */
    public function testGetVisibleScenarios($scenarios, $expected)
    {
        self::dropAndCreateTables();
        $this->fixtureActions();
        $this->fixtureSequences();

        $manager = $this->getManager();
        foreach ($scenarios as $scenario) {
            $manager->save($scenario);
        }
        $persisted = $manager->getAll(null, true);
        self::assertEquals($expected, $persisted);
    }

    /**
     * @dataProvider deleteProvider
     * @param Scenario $expected
     * @param Scenario $scenario
     * @throws \Exception
     */
    public function testDelete($scenario, $expected)
    {
        self::dropAndCreateTables();
        $manager = $this->getManager();
        $manager->save($scenario);
        $manager->delete($expected->id());
        self::expectException('Exception');
        self::expectExceptionMessage('No scenario was found!');
        $manager->getUnique($expected->id());
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function saveProvider()
    {
        return [
            "createScenario" => [
                $this->makeScenario('Test1', []),
                $this->makeScenario('Test1', [], 1)
            ]
        ];
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getAllProvider()
    {
        $sequences = [];

        return [
            "createScenario" => [
                [
                    $this->makeScenario('TestScenario1', $sequences),
                    $this->makeScenario('TestScenario2', $sequences),
                    $this->makeScenario('TestScenario3', $sequences),
                    $this->makeScenario('TestScenario4', $sequences)
                ],
                [
                    $this->makeScenario('TestScenario1', $sequences, '1'),
                    $this->makeScenario('TestScenario2', $sequences, '2'),
                    $this->makeScenario('TestScenario3', $sequences, '3'),
                    $this->makeScenario('TestScenario4', $sequences, '4')
                ]
            ]
        ];
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function getVisibleProvider()
    {
        $sequences = [];

        return [
            "createScenario" => [
                [
                    $this->makeScenario('TestScenario1', $sequences, null, 0, 0),
                    $this->makeScenario('TestScenario2', $sequences, null, 0, 0),
                    $this->makeScenario('TestScenario3', $sequences),
                    $this->makeScenario('TestScenario4', $sequences)
                ],
                [
                    $this->makeScenario('TestScenario3', $sequences, '3',0,1),
                    $this->makeScenario('TestScenario4', $sequences, '4',0,1)
                ]
            ]
        ];
    }

    /**
     * @return array
     * @throws \Exception
     */
    public function deleteProvider()
    {
        $sequences = $this->mockSequences();
        return [
            "deleteScenario" => [
                $this->makeScenario('ScenarioTest', $sequences),
                $this->makeScenario('ScenarioTest', $sequences, 1)
            ]
        ];
    }

    /**
     * @return ScenariosManagerPDO
     */
    public function getManager()
    {
        return $this->getScenariosManager();
    }
}
