<?php

namespace Tests\Model\Scenarios;

use Entity\Scenario\SequenceAction;
use Model\Scenario\SequenceActionManagerPDO;
use Tests\Model\AbstractManagerPDOTest;

/**
 * Class SequenceActionManagerPDOTest
 * @package Tests\Model\Scenarios
 */
class SequenceActionManagerPDOTest extends AbstractManagerPDOTest
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
     * @param SequenceAction $sequenceAction
     * @param SequenceAction $expected
     * @throws \Exception
     */
    public function testSave($sequenceAction, $expected)
    {
        $manager = $this->getManager();
        $manager->save($sequenceAction);
        $persisted = $manager->getUnique($expected->id());

        self::assertEquals($expected, $persisted);

        $res = $manager->getLastInserted('sequence_action');
        self::assertTrue($res == 1);
    }

    /**
     * @dataProvider getAllProvider
     * @param SequenceAction[] $sequenceActions
     * @param SequenceAction[] $expected
     * @throws \Exception
     */
    public function testGetAll($sequenceActions, $expected)
    {
        self::dropAndCreateTables();
        $manager = $this->getManager();
        foreach ($sequenceActions as $sequenceAction) {
            $manager->save($sequenceAction);
        }
        $persisted = $manager->getAll();
        self::assertEquals($expected, $persisted);
    }

    /**
     * @dataProvider deleteProvider
     * @param SequenceAction $sequenceAction
     * @param SequenceAction $expected
     * @throws \Exception
     */
    public function testDelete($sequenceAction, $expected)
    {
        self::dropAndCreateTables();

        $manager = $this->getManager();
        $manager->save($sequenceAction);
        $persisted = $manager->getUnique($expected->id());
        self::assertEquals($expected, $persisted);
        $manager->delete($expected->id());
        $persisted = $manager->getUnique($expected->id());
        self::assertNotEquals($expected, $persisted);
    }

    /**
     * @return SequenceAction[]
     */
    public function saveProvider()
    {
        return [
            "createSequenceAction" => [
                $this->makeSequenceAction(1, 1),
                $this->makeSequenceAction(1, 1, 1)
            ],
            "updateSequenceAction" => [
                $this->makeSequenceAction(1, 2, 1),
                $this->makeSequenceAction(1, 2, 1)
            ]
        ];
    }

    /**
     * @return SequenceAction[]
     */
    public function getAllProvider()
    {
        return [
            "createSequenceActions" => [
                [
                    $this->makeSequenceAction(1, 1),
                    $this->makeSequenceAction(1, 2),
                    $this->makeSequenceAction(1, 3),
                    $this->makeSequenceAction(1, 4)
                ],
                [
                    $this->makeSequenceAction(1, 1, 1),
                    $this->makeSequenceAction(1, 2, 2),
                    $this->makeSequenceAction(1, 3, 3),
                    $this->makeSequenceAction(1, 4, 4)
                ]
            ]
        ];
    }

    /**
     * @return SequenceAction[]
     */
    public function deleteProvider()
    {
        return [
            "deleteSequenceAction" => [
                $this->makeSequenceAction(1, 1),
                $this->makeSequenceAction(1, 1, 1)
            ]
        ];
    }

    /**
     * @return SequenceActionManagerPDO
     */
    public function getManager()
    {
        return $this->getSequenceActionManager();
    }
}
