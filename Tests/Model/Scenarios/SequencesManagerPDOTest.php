<?php

namespace Tests\Model;

use Entity\Scenario\Sequence;
use Model\Scenario\SequencesManagerPDO;

/**
 * Class SequencesManagerPDOTest
 * @package Tests\Model
 */
class SequencesManagerPDOTest extends AbstractManagerPDOTest
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
     * @param Sequence $expected
     * @param Sequence $sequence
     * @throws \Exception
     */
    public function testSave($sequence, $expected)
    {
        self::dropAndCreateTables();
        $this->fixtureActions();

        $manager = $this->getManager();
        $sequenceId = $manager->save($sequence);
        $persisted = $manager->getUnique($expected->id());
        self::assertEquals($expected, $persisted);


        self::assertTrue($sequenceId == 1);

        //Update Test
        $expected->setId(1);
        $expected->setNom('Test2');
        $sequence->setId($sequenceId);
        $sequence->setNom('Test2');

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
        $this->fixtureActions();

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
        $this->fixtureActions();

        $manager = $this->getManager();
        $manager->save($sequence);
        $persisted = $manager->getUnique($expected->id());
        self::assertEquals($expected, $persisted);
        $manager->delete($expected->id());

        self::expectException('Exception');
        self::expectExceptionMessage('No sequence was found!');

        $manager->getUnique($expected->id());
    }

    /**
     * @return array
     */
    public function saveProvider()
    {
        $actions = [];

        return [
            "createSequence" => [
                $this->makeSequence('Test1', $actions),
                $this->makeSequence('Test1', $actions, 1)
            ]
        ];
    }

    /**
     * @return array
     */
    public function getAllProvider()
    {
        $actions = [];

        return [
            "createSequences" => [
                [
                    $this->makeSequence('Test1', $actions),
                    $this->makeSequence('Test2', $actions),
                    $this->makeSequence('Test3', $actions),
                    $this->makeSequence('Test4', $actions)
                ],
                [
                    $this->makeSequence('Test1', $actions, 1),
                    $this->makeSequence('Test2', $actions, 2),
                    $this->makeSequence('Test3', $actions, 3),
                    $this->makeSequence('Test4', $actions, 4)
                ]
            ]
        ];
    }

    /**
     * @return array
     */
    public function deleteProvider()
    {
        $actions = [];

        return [
            "deleteSequence" => [
                $this->makeSequence('Test1', $actions),
                $this->makeSequence('Test1', $actions, 1)
            ]
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
