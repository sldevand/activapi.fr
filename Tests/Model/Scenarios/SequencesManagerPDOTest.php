<?php

namespace Tests\Model;

use Entity\Scenario\Action;
use Entity\Scenario\Sequence;
use Model\Scenario\SequencesManagerPDO;

/**
 * Class SequencesManagerPDOTest
 * @package Tests\Model
 */
class SequencesManagerPDOTest extends AbstractManagerPDOTest
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
     * @param Sequence $expected
     * @param Sequence $sequence
     * @throws \Exception
     */
    public function testSave($sequence, $expected)
    {
        $manager = $this->getManager();
        $manager->save($sequence);
        $persisted = $manager->getUnique($expected->id());
        self::assertEquals($expected, $persisted);
    }

    /**
     * @dataProvider getAllProvider
     * @param Sequence[] $expected
     * @param Sequence[] $sequences
     * @throws \Exception
     */
    public function testGetAll($sequences, $expected)
    {
        self::dropAndCreateTables();
        $manager = $this->getManager();
        foreach ($sequences as $sequence) {
            $manager->save($sequence);
        }
        $persisted = $manager->getAll();
        self::assertEquals($expected, $persisted);
    }

    /**
     * @dataProvider deleteProvider
     * @param Sequence $expected
     * @param Sequence $sequence
     * @throws \Exception
     */
    public function testDelete($sequence, $expected)
    {
        self::dropAndCreateTables();

        $manager = $this->getManager();
        $manager->save($sequence);
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
        return [
            "createSequence" => [
                $this->makeSequence('Test1', $this->mockActions()),
                $this->makeSequence('Test1', $this->mockActions(), 1)
            ],
            "updateSequence" => [
                $this->makeSequence('Test2', $this->mockActions(), 1),
                $this->makeSequence('Test2', $this->mockActions(), 1),
            ]
        ];
    }

    /**
     * @return array
     */
    public function getAllProvider()
    {
        return [
            "createSequences" => [
                [
                    $this->makeSequence('Test1', $this->mockActions()),
                    $this->makeSequence('Test2', $this->mockActions()),
                    $this->makeSequence('Test3', $this->mockActions()),
                    $this->makeSequence('Test4', $this->mockActions())
                ],
                [
                    $this->makeSequence('Test1', $this->mockActions(), 1),
                    $this->makeSequence('Test2', $this->mockActions(), 2),
                    $this->makeSequence('Test3', $this->mockActions(), 3),
                    $this->makeSequence('Test4', $this->mockActions(), 4)
                ]
            ]
        ];
    }

    /**
     * @return array
     */
    public function deleteProvider()
    {
        return [
            "deleteSequence" => [
                $this->makeSequence('Test1', $this->mockActions()),
                $this->makeSequence('Test1', $this->mockActions(), 1)
            ]
        ];
    }

    /**
     * @return Action[]
     */
    public function mockActions()
    {
        $actionneur = $this->makeActionneur('Salon', 1);
        $actionneur2 = $this->makeActionneur('Dalle', 2);
        return [
            $this->makeAction($actionneur, 0),
            $this->makeAction($actionneur2, 100)
        ];
    }

    /**
     * @return SequencesManagerPDO
     */
    public function getManager()
    {
        return $this->getSequencesManager();
    }
}
