<?php

namespace Tests\Model;

use Entity\Scenario\Scenario;
use Entity\Scenario\Sequence;
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
        if (file_exists(SCENARIOS_SQL_PATH)) {
            $sql = file_get_contents(SCENARIOS_SQL_PATH);
            self::$db->exec($sql);
        }
    }

    /**
     * @dataProvider saveProvider
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

        $res = $manager->getLastInserted('scenario');
        self::assertTrue($res == 1);
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
        $manager = $this->getManager();
        foreach ($scenarios as $scenario) {
            $manager->save($scenario);
        }
        $persisted = $manager->getAll();
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
        $persisted = $manager->getUnique($expected->id());
        self::assertEquals($expected, $persisted);
        $manager->delete($expected->id());
        $persisted = $manager->getUnique($expected->id());
        self::assertNotEquals($expected, $persisted);
    }

    /**
     * @return array
     */
    public function saveProvider()
    {
        $sequences = $this->mockSequences();

        return [
            "createScenario" => [
                $this->makeScenario('ScenarioTest', $sequences),
                $this->makeScenario('ScenarioTest', $sequences, 1)
            ],
            "updateScenario" => [
                $this->makeScenario('ScenarioTest2', $sequences, 1),
                $this->makeScenario('ScenarioTest2', $sequences, 1)
            ]
        ];
    }

    /**
     * @return array
     */
    public function getAllProvider()
    {
        $sequences = $this->mockSequences();

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
     * @return Sequence[]
     */
    public function mockSequences()
    {
        $actionneur = $this->makeActionneur('Salon', 0);
        $actionneur2 = $this->makeActionneur('Dalle', 0);
        $actions = [
            $this->makeAction($actionneur, 0),
            $this->makeAction($actionneur2, 100)
        ];
        return [
            $this->makeSequence('Eteindre Salon', $actions)
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
