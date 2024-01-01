<?php

namespace Tests\Model\Scenarios;

use Entity\Scenario\ScenarioSequence;
use Model\Scenario\ScenarioSequenceManagerPDO;
use Tests\Model\AbstractManagerPDOTest;

/**
 * Class ScenarioSequenceManagerPDOTest
 * @package Tests\Model\Scenarios
 */
class ScenarioSequenceManagerPDOTest extends AbstractManagerPDOTest
{
    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        self::dropAndCreateTables();
    }

    public static function dropAndCreateTables()
    {
        self::executeSqlScript(SCENARIOS_SQL_PATH);
    }

    /**
     * @dataProvider saveProvider
     * @param ScenarioSequence $scenarioSequence
     * @param ScenarioSequence $expected
     * @throws \Exception
     */
    public function testSave($scenarioSequence, $expected)
    {
        $manager = $this->getManager();
        $manager->save($scenarioSequence);
        $persisted = $manager->getUnique($expected->id());

        self::assertEquals($expected, $persisted);

        $res = $manager->getLastInserted('scenario_sequence');
        self::assertTrue($res == 1);
    }

    /**
     * @dataProvider getAllProvider
     * @param ScenarioSequence[] $scenarioSequences
     * @param ScenarioSequence[] $expected
     * @throws \Exception
     */
    public function testGetAll($scenarioSequences, $expected)
    {
        self::dropAndCreateTables();
        $manager = $this->getManager();
        foreach ($scenarioSequences as $scenarioSequence) {
            $manager->save($scenarioSequence);
        }
        $persisted = $manager->getAll();
        self::assertEquals($expected, $persisted);
    }

    /**
     * @dataProvider deleteProvider
     * @param ScenarioSequence $scenarioSequence
     * @param ScenarioSequence $expected
     * @throws \Exception
     */
    public function testDelete($scenarioSequence, $expected)
    {
        self::dropAndCreateTables();

        $manager = $this->getManager();
        $manager->save($scenarioSequence);
        $persisted = $manager->getUnique($expected->id());
        self::assertEquals($expected, $persisted);
        $manager->delete($expected->id());
        $persisted = $manager->getUnique($expected->id());
        self::assertNotEquals($expected, $persisted);
    }

    /**
     * @return ScenarioSequence[]
     */
    public function saveProvider()
    {
        return [
            "createScenarioSequence" => [
                $this->makeScenarioSequence(1, 1),
                $this->makeScenarioSequence(1, 1, 1)
            ],
            "updateScenarioSequence" => [
                $this->makeScenarioSequence(1, 2, 1),
                $this->makeScenarioSequence(1, 2, 1)
            ]
        ];
    }

    /**
     * @return ScenarioSequence[]
     */
    public function getAllProvider()
    {
        return [
            "createScenarioSequences" => [
                [
                    $this->makeScenarioSequence(1, 1),
                    $this->makeScenarioSequence(1, 2),
                    $this->makeScenarioSequence(1, 3),
                    $this->makeScenarioSequence(1, 4)
                ],
                [
                    $this->makeScenarioSequence(1, 1, 1),
                    $this->makeScenarioSequence(1, 2, 2),
                    $this->makeScenarioSequence(1, 3, 3),
                    $this->makeScenarioSequence(1, 4, 4)
                ]
            ]
        ];
    }

    /**
     * @return ScenarioSequence[]
     */
    public function deleteProvider()
    {
        return [
            "deleteScenarioSequence" => [
                $this->makeScenarioSequence(1, 1),
                $this->makeScenarioSequence(1, 1, 1)
            ]
        ];
    }

    /**
     * @return ScenarioSequenceManagerPDO
     */
    public function getManager()
    {
        return $this->getScenarioSequenceManager();
    }
}
