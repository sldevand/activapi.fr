<?php

namespace Tests\Model\Scenarios;

use Entity\Scenario\ScenarioSequence;
use Model\Scenario\ScenarioSequenceManagerPDO;
use Tests\Model\AbstractManagerPDOTest;

class ScenarioSequenceManagerPDOTest extends AbstractManagerPDOTest
{

    public static function setUpBeforeClass()
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
     * @param ScenarioSequence $entity
     * @param ScenarioSequence $expected
     * @throws \Exception
     */
    public function testSave($entity, $expected)
    {
        $manager = $this->getManager();
        $manager->save($entity);
        $persisted = $manager->getUnique($expected->id());

        self::assertEquals($expected, $persisted);

        $res = $manager->getLastInserted('scenario_sequence');
        self::assertTrue($res == 1);
    }

    public function testGetAll($entities, $expected)
    {
        // TODO: Implement testGetAll() method.
    }

    public function testDelete($entity, $expected)
    {
        // TODO: Implement testDelete() method.
    }

    public function saveProvider()
    {
        return [
            "createScenarioSequence" => [
                $this->makeScenarioSequence(1, 1),
                $this->makeScenarioSequence(1, 1, 1)
            ]
        ];
    }

    public function getAllProvider()
    {
        // TODO: Implement getAllProvider() method.
    }

    public function deleteProvider()
    {
        // TODO: Implement deleteProvider() method.
    }

    /**
     * @return ScenarioSequenceManagerPDO
     */
    public function getManager()
    {
        return $this->getScenarioSequenceManager();
    }
}
